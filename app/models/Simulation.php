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
<<<<<<< HEAD

            if (!$stmt) {
                throw new \Exception("Erreur préparation requête sim_save");
            }

            $result = $stmt->execute([$userId, $description]);

            if (!$result) {
                throw new \Exception("Erreur exécution INSERT sim_save");
=======
            
            if (!$stmt) {
                throw new \Exception("Erreur préparation sim_save: " . print_r($this->db->errorInfo(), true));
            }
            
            $result = $stmt->execute([$userId, $description]);
            
            if (!$result) {
                throw new \Exception("Erreur exécution sim_save: " . print_r($stmt->errorInfo(), true));
>>>>>>> origin/rova_metier
            }

            $saveId = $this->db->lastInsertId();
            error_log("SaveId créé: " . $saveId);

<<<<<<< HEAD
            // 2. Sauvegarder TOUS LES DONS actuels
            $sqlDons = "SELECT 
                            d.id,
                            d.id_ville,
                            d.id_besoin,
                            d.nom_donneur,
                            d.quantite,
                            b.prix,
                            b.id_type_besoin,
                            t.type_besoin
                        FROM don d
                        JOIN besoin b ON d.id_besoin = b.id
                        LEFT JOIN type_besoin t ON b.id_type_besoin = t.id";
            
            $stmtDons = $this->db->query($sqlDons);
            $dons = $stmtDons->fetchAll();
            
            error_log("Nombre de dons à sauvegarder: " . count($dons));

            $sqlInsertDon = "INSERT INTO sim_don 
                            (id_save, id_don, id_ville, id_besoin, nom_donneur, quantite, montant, type_besoin) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtInsertDon = $this->db->prepare($sqlInsertDon);

            foreach ($dons as $don) {
                $montant = $don['quantite'] * $don['prix'];
                $result = $stmtInsertDon->execute([
                    $saveId,
                    $don['id'],
                    $don['id_ville'],
                    $don['id_besoin'],
                    $don['nom_donneur'],
                    $don['quantite'],
                    $montant,
                    $don['type_besoin']
                ]);

                if (!$result) {
                    throw new \Exception("Erreur insertion sim_don");
                }
            }

            error_log("Dons sauvegardés avec succès");

            // 3. Sauvegarder TOUS LES BESOINS actuels
            $sqlBesoins = "SELECT 
                                vb.id,
                                vb.id_ville,
                                vb.id_besoin,
                                vb.quantite,
                                b.prix,
                                b.id_type_besoin,
                                t.type_besoin
                            FROM ville_besoin vb
                            JOIN besoin b ON vb.id_besoin = b.id
                            LEFT JOIN type_besoin t ON b.id_type_besoin = t.id";
            
            $stmtBesoins = $this->db->query($sqlBesoins);
            $besoins = $stmtBesoins->fetchAll();
            
            error_log("Nombre de besoins à sauvegarder: " . count($besoins));

            $sqlInsertBesoin = "INSERT INTO sim_besoin 
                                (id_save, id_ville_besoin, id_ville, id_besoin, quantite, prix_unitaire, type_besoin) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsertBesoin = $this->db->prepare($sqlInsertBesoin);

            foreach ($besoins as $besoin) {
                $result = $stmtInsertBesoin->execute([
                    $saveId,
                    $besoin['id'],
                    $besoin['id_ville'],
                    $besoin['id_besoin'],
                    $besoin['quantite'],
                    $besoin['prix'],
                    $besoin['type_besoin']
                ]);

                if (!$result) {
                    throw new \Exception("Erreur insertion sim_besoin");
                }
            }

            error_log("Besoins sauvegardés avec succès");

            // 4. Sauvegarder les ACHATS actuels
            $sqlAchats = "SELECT 
                                a.id,
                                a.id_ville,
                                a.id_besoin,
                                a.quantite,
                                a.montant_total,
                                b.id_type_besoin,
                                t.type_besoin
                            FROM achat a
                            JOIN besoin b ON a.id_besoin = b.id
                            LEFT JOIN type_besoin t ON b.id_type_besoin = t.id";
            
            $stmtAchats = $this->db->query($sqlAchats);
            $achats = $stmtAchats->fetchAll();
            
            error_log("Nombre d'achats à sauvegarder: " . count($achats));

            $sqlInsertAchat = "INSERT INTO sim_achat 
                                (id_save, id_ville, id_besoin, quantite, montant_total, type_source) 
                                VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsertAchat = $this->db->prepare($sqlInsertAchat);

            foreach ($achats as $achat) {
                $typeSource = 'don_';
                if (!empty($achat['type_besoin'])) {
                    $typeSource .= strtolower($achat['type_besoin']);
                } else {
                    $typeSource .= 'inconnu';
                }
                $result = $stmtInsertAchat->execute([
                    $saveId,
                    $achat['id_ville'],
                    $achat['id_besoin'],
                    $achat['quantite'],
                    $achat['montant_total'],
                    $typeSource
                ]);

                if (!$result) {
                    throw new \Exception("Erreur insertion sim_achat");
                }
            }

            error_log("Achats sauvegardés avec succès");
=======
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
<<<<<<< HEAD
        $sql = "SELECT * FROM sim_don WHERE id_save = ? ORDER BY type_besoin, id_don";
=======
        $sql = "SELECT * FROM sim_don WHERE id_save = ?";
>>>>>>> origin/rova_metier
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
    }

    public function getBesoinsBySave($saveId)
    {
<<<<<<< HEAD
        $sql = "SELECT * FROM sim_besoin WHERE id_save = ? ORDER BY id_ville, id_besoin";
=======
        $sql = "SELECT * FROM sim_besoin WHERE id_save = ?";
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD
        $sql = "SELECT * FROM sim_resultat WHERE id_save = ? ORDER BY id_ville, id_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        $resultats = $stmt->fetchAll();

        error_log("getResultatsBySave($saveId) retourne " . count($resultats) . " résultats");

        return $resultats;
=======
        $sql = "SELECT * FROM sim_resultat WHERE id_save = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$saveId]);
        return $stmt->fetchAll();
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD
                // Récupérer le don original
                $sqlDon = "SELECT id FROM don WHERE id_ville = ? AND id_besoin = ?";
                $stmtDon = $this->db->prepare($sqlDon);
                $stmtDon->execute([$resultat['id_ville'], $resultat['id_besoin']]);
                $don = $stmtDon->fetch();

                if ($don) {
                    $sqlUpdate = "UPDATE don SET quantite = quantite - ? WHERE id = ? AND quantite >= ?";
                    $stmtUpdate = $this->db->prepare($sqlUpdate);
                    $stmtUpdate->execute([
                        $resultat['quantite_proposee'],
                        $don['id'],
                        $resultat['quantite_proposee']
                    ]);
                    error_log("Don réduit: id={$don['id']}, quantité -{$resultat['quantite_proposee']}");
                }
                    
                $sqlUpdate = "UPDATE don SET quantite = quantite - ? 
                            WHERE id_ville = ? AND id_besoin = ? AND quantite >= ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    $resultat['quantite_proposee'],
                    $resultat['id_ville'],
                    $resultat['id_besoin'],
                    $resultat['quantite_proposee']
                ]);
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD
                error_log("Besoin réduit: ville={$resultat['id_ville']}, besoin={$resultat['id_besoin']}, quantité -{$resultat['quantite_proposee']}");
=======
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
>>>>>>> origin/rova_metier
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

<<<<<<< HEAD
            error_log("Sauvegarde trouvée: id={$save['id']}, date={$save['date_save']}");
=======
            error_log("Sauvegarde trouvée: id={$save['id']}");
>>>>>>> origin/rova_metier

            // Restaurer les dons
            $sqlDons = "SELECT * FROM sim_don WHERE id_save = ?";
            $stmtDons = $this->db->prepare($sqlDons);
            $stmtDons->execute([$save['id']]);
            $donsSauvegardes = $stmtDons->fetchAll();

            foreach ($donsSauvegardes as $donSauv) {
                $sqlUpdate = "UPDATE don SET quantite = ? WHERE id = ?";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->execute([$donSauv['quantite'], $donSauv['id_don']]);
<<<<<<< HEAD
                error_log("Don restauré: id={$donSauv['id_don']}, quantité={$donSauv['quantite']}");
=======
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD
                error_log("Besoin restauré: id={$besoinSauv['id_ville_besoin']}, quantité={$besoinSauv['quantite']}");
            }

            // Supprimer les achats créés aprés la sauvegarde
            $sqlDelete = "DELETE FROM achat WHERE date_achat > ?";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->execute([$save['date_save']]);
            error_log("Achats supprimés après la sauvegarde");
=======
            }

            // Supprimer les achats créés après la sauvegarde
            $sqlDelete = "DELETE FROM achat WHERE date_achat > ?";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->execute([$save['date_save']]);
>>>>>>> origin/rova_metier

            $this->db->commit();
            error_log("=== RÉINITIALISATION RÉUSSIE ===");
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Erreur réinitialisation: " . $e->getMessage());
            return false;
        }
    }

<<<<<<< HEAD
    // === NETTOYAGE ===

    public function nettoyerAnciennesSimulations($userId)
    {
        $sql = "DELETE FROM sim_save WHERE user_id = ? AND date_save < DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }

=======
>>>>>>> origin/rova_metier
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
<<<<<<< HEAD

=======
>>>>>>> origin/rova_metier
?>