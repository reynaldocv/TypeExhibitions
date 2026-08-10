<?php
$caracteres_sem_acento = array(
  'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
  'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
  'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
  'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
  'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
  'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
  'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
  'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T', "ł" => "l"
);




$count = 0;
$o_items = $this->getVar('items');

?>

<?php print "<h3>Lista de Artistas </h3>" ?>
  <div class="contenedor">
        
          <div class="control-box rounded">
            <div class="control-box-left-content">
              <div class="simple-search-box"> Search: 
                <input type="text" id="myFilter" value="<?php print $labels ?>" size="60">
              </div>
            </div>
            <div id="btn-ulan">
              <input type="submit" value="Consultar" id="btn_consultar-ulan" onclick='roboto(1)'>
            </div>
          </div>                  
        <div id="resultado-ulan">
        </div>     
    </div>  

<div class="container">
  <table class="listtable">
    <thead>
      <tr>
        <th>#</th>
        <th>ID</th>        
        <th>NAMES</th>
        <th>Wikidata</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody id="myTable">

<?php 

/*caNavUrl($this->request, '', 'Search', 'objects', array('search' => 'entity_id:^ca_entities.entity_id/EntityRel:100'), 
array('dontURLEncodeParameters' => true)); ?>

//the URL looked like this:

//index.php/Search/objects/search/entity_id:8744/EntityRel:100
*/
$urlULAN = "http://vocab.getty.edu/page/ulan/";
$urlWIKI = "https://www.wikidata.org/wiki/";
$cnt = 0; 

while($o_items->nextHit()) {  
  $id = $o_items->get("ca_entities.rank");
  $idno = $o_items->get("ca_entities.idno"); 
  $name= $o_items->get("ca_entities.preferred_labels"); 

  $wikicode = $o_items->get("ca_entities.IDcodes.wikicode"); 
  $wikicodeUrl = "<a href='$urlWIKI$wikicode' target='_blank'> $wikicode </a>";
  
  $ulancode = $o_items->get("ca_entities.IDcodes.ulancode"); 
  //$ulancode = "<a href='https://www.getty.edu/vow/ULANFullDisplay?find=&role=&nation=&subjectid=$ulancode'> $ulancode </a>";
  $ulancodeUrl = "<a href='$urlULAN$ulancode'  target='_blank'> $ulancode </a>";
  
  $type = $o_items->get("type_id");

  if ($type == "488")
  //if ($type == "488" && trim($wikicode) == "")
  {
    $cnt += 1;
  ?> 

    <tr>
      <td><?php print $cnt ?> </td>
  
      <td>      
        <a target='blank' href="<?php print caNavUrl($this->request, 'editor', 'entities', 'EntityEditor/Edit/entity_id/'.$id); ?>"> <?php echo $id ?></a>
      </td>
      <td>
        <?php 
          print "$name";
        ?>
        <input type='hidden' id="name-<?php print $cnt ?>" value="<?php print strtr($name, $caracteres_sem_acento) ?>"/>
        <input type='hidden' id="idno-<?php print $cnt ?>" value="<?php print $id ?>"/>
      </td>
      <td>        
        <?php print "<div id='wikicode-$cnt'></div>"; ?>      
      </td>
      <td>
        <?php print "<div id='status-$cnt'></div>"; ?>
      </td>
      </tr>
    <?php 

  }    
}

print "</table><br><br><br><br><br><br><br>";

/*while($o_items->nextHit()) {
    print "Hit ".$count.": ".$o_items->get('ca_objects.preferred_labels.name')."<br/>\n";
    $count++;
}*/
?>


  </tbody> 
  <input type='text' id="total" value='<?php print $cnt?>'/>
</div>


<script>
    $(document).ready(function(){
      $("#myFilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
  </script>
  <script>
    const limit = document.querySelector("#total").value.trim();
    function roboto(idx)
    {
        if (idx < limit){

          var idno = document.querySelector("#idno-" + idx.toString()).value.trim();
          var consulta = document.querySelector("#name-" + idx.toString()).value.trim();
          var option = 0; 
          
          var divWikicode = "#wikicode-" + idx.toString(); 
          var divStatus = "#status-" + idx.toString(); 
  
          jQuery(divStatus).html("Searching... <i class='fa fa-spinner fa-spin'></i>");

          jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'QueryToWikidata'); ?>', {consulta}, function(data) {    
            var total = data['search'].length;   

            jQuery(divStatus).html(total + " result(s)"); 

            if (total == 1)
            {
              const code = data['search'][0]['id']; 
              const label = data['search'][0]['label']; 
              const description = data['search'][0]['description']; 

              jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'SaveCodes'); ?>', {option, idno, code}, function(data1) {    

                 jQuery(divStatus).html(data1["results"]); 
                 jQuery(divWikicode).html(label + " " + description);   
              }); 
             }


            
            roboto(idx + 1); 
          }); 

        }
        
    }
     
    
</script>
