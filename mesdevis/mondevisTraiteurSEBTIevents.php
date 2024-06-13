
            <?php
                  require "../packages/vendor/autoload.php";
                  use Dompdf\Dompdf;
                  use Dompdf\Options;
                  $options = new Options;
                  $options->setIsRemoteEnabled(true);
                  $dompdf = new Dompdf([
                    "chroot" => "../imgs"
                  ]);
                  $html = file_get_contents("../interfaceclient/fournisseur/contenupdfdevis.html");
                  $html = str_replace([ "{{ nomgenerateur }}","{{ adress }}", "{{ profil }}", "{{ email }}", "{{activite}}", "{{ nomF }}", "{{ nomEvenement }}", "{{ dateEvenement }}", "{{ date }}", "{{ contenu }}", "{{ services }}"], [  "HandInHand","4 éme Etg Bureau 44, Lotissement NASROLLAH, Lot 341, Berrechid","ACHIH.png","handinhand@gmail.com", "Activité sociale", "Traiteur SEBTI events","Excursion pour les aînés", "2024-06-10 11:01:00", "2024-06-10", "10%", "Inclure votre nom ou celui de votre organisation dans tous nos supports de communication avant, pendant et après l'événement, ce qui permettra d'accroître votre réputation."], $html);
          $html = str_replace(["{{ Produit 1 }}",  "{{ PU 1 }}", "{{ Designation 1 }}", "{{ Prix 1 }}", "{{ Quantite 1 }}"], ["Plat marocain", "1500","Filet de pageot rôti aux épices douces et câpres, trilogie de poivrons, bille de légumes de saison pour 10 personnes","4500", "3"], $html);
          
          $html = str_replace(["{{ Produit 2 }}",  "{{ PU 2 }}", "{{ Designation 2 }}", "{{ Prix 2 }}", "{{ Quantite 2 }}"], ["Plat marocain", "1200","Plat Cuisse de canard confite pour 10 personnes","3600", "3"], $html);
          
      $html = str_replace(["{{ total }}"], ["8100"], $html);
    
                    $dompdf->loadHtml($html);
                    $dompdf->render();
                    $dompdf->stream("mondevis.pdf", ["Attachment" => 0]);
                    