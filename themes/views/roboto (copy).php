<?php

$caracteres_sem_acento = array(
  'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
  'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
  'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
  'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
  'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
  'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
  'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
  'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T', "ł" => "l", 
  "š"=>'s', 'ć' => 'i',
);

// decode three byte unicode characters $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/", "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'", $string); // decode two byte unicode characters $string = preg_replace("/([\300-\337])([\200-\277])/", "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'", $string); return $string; }


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
        <th>Comment</th>
      </tr>
    </thead>
    <tbody id="myTable">

<?php 

/*caNavUrl($this->request, '', 'Search', 'objects', array('search' => 'entity_id:^ca_entities.entity_id/EntityRel:100'), 
array('dontURLEncodeParameters' => true)); ?>

//the URL looked like this:

//index.php/Search/objects/search/entity_id:8744/EntityRel:100
*/
$option = "WIKI";

if ($option == "WIKI")
{
  $link = "https://www.wikidata.org/wiki/";
}
else{
  $link ="http://vocab.getty.edu/page/ulan/";
}

$cnt = 0; 

while($o_items->nextHit()) {  
  $id = $o_items->get("ca_entities.rank");
  $idno = $o_items->get("ca_entities.idno"); 
  $name= $o_items->get("ca_entities.preferred_labels"); 

  $_name = strtr($name, $caracteres_sem_acento);
  //$_name = utf8_encode($name);

  if ($option == "WIKI")
  {
    $_code = $o_items->get("ca_entities.wikidata.wikicode"); 
    $_url = $o_items->get("ca_entities.wikidata.wikiurl");
    $_comment= $o_items->get("ca_entities.wikidata.wikicomment"); 
  }
  else
  {

  }
  //$ulancode = $o_items->get("ca_entities.IDcodes.ulancode"); 
  //$ulancode = "<a href='https://www.getty.edu/vow/ULANFullDisplay?find=&role=&nation=&subjectid=$ulancode'> $ulancode </a>";
  //$ulancodeUrl = "<a href='$urlULAN$ulancode'  target='_blank'> $ulancode </a>";
  
  $type = $o_items->get("type_id");

  //if ($type == "488")
  if ($type == "488" && $_comment == "")
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
          print "$name $_name" ;
        ?>
        <input type='hidden' id="name-<?php print $cnt ?>" value="<?php print $_name ?>"/>
        <input type='hidden' id="idno-<?php print $cnt ?>" value="<?php print $id ?>"/>
      </td>
      <td>        
        <?php print "<div id='wikicode-$cnt'> $_code </div>";  ?>      
      </td>
      <td>
        <?php print "<div id='status-$cnt'> $_comment </div>"; ?>
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
          var option = "WIKI"; 
          
          var divWikicode = "#wikicode-" + idx.toString(); 
          var divStatus = "#status-" + idx.toString(); 
  
          jQuery(divStatus).html("Searching... <i class='fa fa-spinner fa-spin'></i>");

          jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'QueryToWikidata'); ?>', {consulta}, function(data) {    
            var total = data['search'].length;   

            if (total == 1)
            {
              //alert(data['search'][0]['id']);
              const _code = data['search'][0]['id']; 
              const _url =  "https://www.wikidata.org/wiki/" + _code; 
              const _comment = data['search'][0]['label'] + ": "+ data['search'][0]['description']; 

              jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'SaveCodes'); ?>', {option, idno, _code, _url, _comment}, function(data1) {    

                jQuery(divWikicode).html(data1["results"]);   
                jQuery(divStatus).html(data1["status"]); 
                 
              }); 
             }
             else
             {
              const _code = ""; 
              const _url = ""; 
              const _comment = total + " result(s)"; 
              
              jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'SaveCodes'); ?>', {option, idno, _code, _url, _comment}, function(data2) {    
                 jQuery(divWikicode).html(data2["results"]);  
                 jQuery(divStatus).html(data2["status"]);  
              });

             }


            
            roboto(idx + 1); 
          }); 

        }
        
    }
     
    
</script>
