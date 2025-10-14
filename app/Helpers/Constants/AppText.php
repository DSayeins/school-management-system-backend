<?php

    namespace App\Helpers\Constants;

    class AppText
    {
        static public function isset(): string
        {
            return "Cet enregistrement existe en base de donnée";
        }

        static public function successfullyCreate(): string
        {
            return "Enregistrement effectué avec succes.";
        }

        static public function successfullyUpdate(): string
        {
            return "Modification effectuée avec succes.";
        }

        static public function notFound(string $value = ''): string
        {
            if ($value) {
                return "$value n'a pas été trouver dans la base de donnée";
            }

            return "Cet enregistrement n'existe pas en base de donnée";
        }

        static public function impossibleToDelete(): string
        {
            return "Impossible de supprimer cet enregistrement, car il est lié à d'autres enregistrements.";
        }

        static public function successfullyDelete(): string
        {
            return "Suppression effectué avec succes.";
        }

        static public function validationError(): string
        {
            return "Erreur de validation";
        }

        static public function yearNotFound(): string
        {
            return "Le parametre année n'est pas present dans la requete.";
        }

        static public function levelNotFound(): string
        {
            return "Le parametre niveau n'est pas present dans la requete.";
        }

        static public function parameterNotFound(): string
        {
            return "Il manque des paramètres.";
        }

        static public function badData(): string
        {
            return "Les données renseignées sont erronées.";
        }

        static public function invalidUri(): string
        {
            return "Votre url est invalide";
        }

        static public function feedNotfound(): string
        {
            return "Les frais de scolarité pour cette année n'ont pas été défini";
        }

        static public function configNotFound(): string
        {
            return "Le fichier configuration pour cette année n'a pas été défini";
        }

        static public function paymentResetSuccessfully(string $name = ''): string
        {
            return "Les transactions pour $name ont été réinitialiser avec succés.";
        }
    }
