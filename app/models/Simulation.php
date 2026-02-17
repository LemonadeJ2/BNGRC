<?php
namespace app\models;

class Simulation
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // === SAUVEGARDE DE L'ÉTAT AVANT SIMULATION ===

    public function sauvegarderEtatAvant($userId, $description = '')
    {
        try {
            error_log("=== DÉBUT SAUVEGARDE ÉTAT ===");
            error_log("userId: $userId, description: $description");

            $this->db->beginTransaction();

            // 1. Créer un point de sauvegarde
            $sql = "INSERT INTO sim_save (date_save, user_id, description) VALUES (NOW(), ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                throw new \Exception("Erreur préparation sim_save: " . print_r($this->db->errorInfo(), true));
            }
            
            $result = $stmt->execute([$userId, $description]);
            
            if (!$result) {
                throw new \Exception("Erreur exécution sim_save: " . print_r($stmt->errorInfo(), true));
            }

            $saveId = $this->db->lastInsertId();
            error_log("SaveId créé: " . $saveId);

            // 2. Sauvegarder les dons
            $sql = "INSERT INTO sim_don (id_save, id_don, id_ville, id_besoin, nom_donneur, quantite, montant, type_besoin)
                    SELECT ?, id, id_ville, id_besoin, nom_donneur, quantite, 
                           quantite * (SELECT prix FROM besoin WHERE id = don.id_besoin) AS montant,
                           (SELECT type_besoin FROM type_besoin WHERE id = (SELECT id_type_besoin FROM besoin WHERE id = don.id_besoin))
                    FROM don";
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                throw new \Exception("Erreur préparation sim_don: " . print_r($this->db->errorInfo(), true));
            }
            
            $result = $stmt->execute([$saveId]);
            
            if (!$result) {
                throw new \Exception("Erreur exécution sim_don: " . print_r($stmt->errorInfo(), true));
            }
            
            $rowCount = $stmt->rowCount();
            error_log("Dons sauvegardés: $rowCount lignes");

            // 3. Sauvegarder les besoins
            $sql = "INSERT INTO sim_besoin (id_save, id_ville_besoin, id_ville, id_besoin, quantite, prix_unitaire, type_besoin)
                    SELECT ?, id, id_ville, id_besoin, quantite, 
                           (SELECT prix FROM besoin WHERE id = ville_besoin.id_besoin) AS prix_unitaire,
                           (SELECT type_besoin FROM type_besoin WHERE id = (SELECT id_type_besoin FROM besoin WHERE id = ville_besoin.id_besoin))
                    FROM ville_besoin";
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                throw new \Exception("Erreur préparation sim_besoin: " . print_r($this->db->errorInfo(), true));
            }
            
            $result = $stmt->execute([$saveId]);
            
            if (!$result) {
                throw new \Exception("Erreur exécution sim_besoin: " . print_r($stmt->errorInfo(), true));
            }
            
            $rowCount = $stmt->rowCount();
            error_log("Besoins sauvegardés: $rowCount lignes");

            // 4. Sauvegarder les achats (optionnel)
            try {
                $sql = "INSERT INTO sim_achat (id_save, id_ville, id_besoin, quantite, montant_total, type_source)
                        SELECT ?, id_ville, id_besoin, quantite, montant_total, 'don_argent'
                        FROM achat";
                $stmt = $this->db->prepare($sql);
                if ($stmt) {
                    $stmt->execute([$saveId]);
                    $rowCount = $stmt->rowCount();
                    error_log("Achats sauvegardés: $rowCount lignes");
                }
            } catch (\Exception $e) {
                error_log("Note: Table achat peut-être vide: " . $e->getMessage());
            }

            $this->db->commit();
            error_log("=== SAUVEGARDE RÉUSSIE - ID: $saveId ===");
            return $saveId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("ERREUR dans sauvegarderEtatAvant: " . $e->getMessage());
            return false;
        }
    }

    // === SAUVEGARDE DES RÉSULTATS DE SIMULATION ===

    public function sauvegarderResultatSimulation($saveId, $idVille, $idBesoin, $quantite, $provenance, $montant)
    {
        $sql = "INSERT INTO sim_resultat (id_save, id_ville, id_besoin, quantite_proposee, provenance, montant_utilise) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$saveId, $idVille, $idBesoin, $quantite, $provenance, $montant]);
    }

    // === RÉCUPÉRATION DES DONNÉES ===

    public function getDonsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_don WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getBesoinsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_besoin WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getAchatsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_achat WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getResultatsBySave($saveId)
    {
        $sql = "SELECT * FROM sim_resultat WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getDerniereSauvegarde($userId)
    {
        $sql = "SELECT * FROM sim_save WHERE user_id = ? ORDER BY date_save DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // === VALIDATION (APPLICATION À LA VRAIE BDD) ===

    public function validerSimulation($saveId)
    {
        try {
            $this->db->beginTransaction();

            error_log("=== DÉBUT VALIDATION SIMULATION $saveId ===");

            // Récupérer les résultats
            $resultats = $this->getResultatsBySave($saveId);
            error_log("Nombre de résultats à valider: " . count($resultats));

            // 1. Réduire les quantités de DONS
            foreach ($resultats as $resultat) {
                $sqlUpdate = "UPDATE don SET quantite = quantite - ? 
                            WHERE id_ville = ? AND id_besoin = ? AND quantite >= ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    $resultat['quantite_proposee'],
                    $resultat['id_ville'],
                    $resultat['id_besoin'],
                    $resultat['quantite_proposee']
                ]);
            }

            // 2. Réduire les quantités de BESOINS
            foreach ($resultats as $resultat) {
                $sqlUpdate = "UPDATE ville_besoin SET quantite = quantite - ? 
                            WHERE id_ville = ? AND id_besoin = ? AND quantite >= ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    $resultat['quantite_proposee'],
                    $resultat['id_ville'],
                    $resultat['id_besoin'],
                    $resultat['quantite_proposee']
                ]);
            }

            // 3. Créer des achats si nécessaire
            foreach ($resultats as $resultat) {
                if (strpos($resultat['provenance'], 'Achat') !== false) {
                    $sqlInsert = "INSERT INTO achat (id_ville, id_besoin, quantite, montant_total, date_achat)
                                VALUES (?, ?, ?, ?, NOW())";
                    $stmtInsert = $this->db->prepare($sqlInsert);
                    $stmtInsert->execute([
                        $resultat['id_ville'],
                        $resultat['id_besoin'],
                        $resultat['quantite_proposee'],
                        $resultat['montant_utilise']
                    ]);
                }
            }

            $this->db->commit();
            error_log("=== VALIDATION RÉUSSIE ===");
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur validation: " . $e->getMessage());
            return false;
        }
    }

    // === RÉINITIALISATION (ROLLBACK) ===

    public function reinitialiserDerniereValidation($userId)
    {
        try {
            $this->db->beginTransaction();

            error_log("=== DÉBUT RÉINITIALISATION ===");

            // Récupérer la dernière sauvegarde
            $sql = "SELECT * FROM sim_save 
                    WHERE user_id = ? 
                    ORDER BY date_save DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $save = $stmt->fetch();

            if (!$save) {
                throw new \Exception("Aucune sauvegarde trouvée");
            }

            error_log("Sauvegarde trouvée: id={$save['id']}");

            // Restaurer les dons
            $sqlDons = "SELECT * FROM sim_don WHERE id_save = ?";
            $stmtDons = $this->db->prepare($sqlDons);
            $stmtDons->execute([$save['id']]);
            $donsSauvegardes = $stmtDons->fetchAll();

            foreach ($donsSauvegardes as $donSauv) {
                $sqlUpdate = "UPDATE don SET quantite = ? WHERE id = ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([$donSauv['quantite'], $donSauv['id_don']]);
            }

            // Restaurer les besoins
            $sqlBesoins = "SELECT * FROM sim_besoin WHERE id_save = ?";
            $stmtBesoins = $this->db->prepare($sqlBesoins);
            $stmtBesoins->execute([$save['id']]);
            $besoinsSauvegardes = $stmtBesoins->fetchAll();

            foreach ($besoinsSauvegardes as $besoinSauv) {
                $sqlUpdate = "UPDATE ville_besoin SET quantite = ? WHERE id = ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([$besoinSauv['quantite'], $besoinSauv['id_ville_besoin']]);
            }

            // Supprimer les achats créés après la sauvegarde
            $sqlDelete = "DELETE FROM achat WHERE date_achat > ?";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->execute([$save['date_save']]);

            $this->db->commit();
            error_log("=== RÉINITIALISATION RÉUSSIE ===");
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur réinitialisation: " . $e->getMessage());
            return false;
        }
    }

    public function getDonsDisponibles($saveId)
    {
        $sql = "SELECT sd.*, 
                   (sd.montant - COALESCE(SUM(sr.montant_utilise), 0)) AS montant_restant
            FROM sim_don sd
            LEFT JOIN sim_resultat sr ON sr.id_save = sd.id_save 
                AND sr.provenance LIKE CONCAT('%', sd.nom_donneur, '%')
            WHERE sd.id_save = ?
            GROUP BY sd.id
            HAVING montant_restant > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }
}
?>