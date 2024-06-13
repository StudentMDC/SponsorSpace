
                <?php
                require "../packages/vendor/autoload.php";
                use Dompdf\Dompdf;
                use Dompdf\Options;
                $options = new Options;
                $options->setIsRemoteEnabled(true);
                $dompdf = new Dompdf($options);
                $html = file_get_contents("../interfaceclient/gdo/contenupdfdemande.html");
                $html = str_replace([ "{{ nomgenerateur }}", "{{ adress }}", "{{ email }}", "{{ activite }}", "{{ nomGdo }}", "{{ nomEvenement }}", "{{ dateEvenement }}", "{{ date }}", "{{ prixE }}", "{{ contenu }}", "{{ services }}"], [  "HandInHand", "4 éme Etg Bureau 44, Lotissement NASROLLAH, Lot 341, Berrechid", "handinhand@gmail.com", "Activité sociale", "Credit Agricole Du Maroc","Excursion pour les aînés", "2024-06-10 11:02:00", "2024-06-10", "10000", "Ce projet vise à offrir une journée de détente et de convivialité à nos aînés, leur permettant de profiter d'activités enrichissantes et de renforcer les liens sociaux. Votre soutien serait crucial pour la réussite de cette initiative et pour assurer le bien-être de nos participants.", "Inclure votre nom ou celui de votre organisation dans tous nos supports de communication avant, pendant et après l'événement, ce qui permettra d'accroître votre visibilité et votre réputation auprès de notre communauté et de nos partenaires."], $html);
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream("mademande.pdf", ["Attachment" => 0]);
                