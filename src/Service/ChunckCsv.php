<?php


namespace App\Service;

class ChunckCsv
{
    // Découpage du CSV en plusieurs fichiers pour éviter l'erreur du memory_limit.
    private function cuttingCsv($csvFile)
    {
        $row_count_limit = 1000;
        $file_counter = 1;
        $file_name = 'tickets_appels_201202';
        $file_ext = '.csv';
        $row = 1;
        $ctr = 0;
        $cols = array();

        if (($handle_src = fopen("./$file_name" . ".csv", "r")) !== FALSE) {

            $cols = fgetcsv($handle_src, 200, ";");

            if (($handle_dest = fopen("./$file_name" . $file_counter . $file_ext, "a+")) !== FALSE) {
                fputcsv($handle_dest, $cols);

                while (($data = fgetcsv($handle_src, 200, ";")) !== FALSE) {
                    $num = count($data);
                    fputcsv($handle_dest, $data);
                    $row++;

                    if ($row == $row_count_limit) {
                        fclose($handle_dest);
                        $row = 1;
                        echo "\t Création du fichier  --> " . $file_name . $file_counter . $file_ext . "\n";
                        $handle_dest = fopen("./$file_name" . $file_counter . $file_ext, "a+");
                        $file_counter++;
                        fputcsv($handle_dest, $cols);

                    }
                }
                fclose($handle_src);
            }
        } else {
            throw new \Exception('Erreur dans le fichier');
        }
    }

}
