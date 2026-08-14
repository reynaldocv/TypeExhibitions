<?php


$count = 0;
$o_items = $this->getVar('items');
//var_dump($o_items); 
?>

<?php print "<h3>Lista de Exposições </h3>" ?>
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
        <th>TYPE</th>
        <th>Exhibition</th>
        <th>Status</th>
        <th>New status</th>
      </tr>
    </thead>
    <tbody id="myTable">

<?php 

/*caNavUrl($this->request, '', 'Search', 'objects', array('search' => 'entity_id:^ca_entities.entity_id/EntityRel:100'), 
array('dontURLEncodeParameters' => true)); ?>

//the URL looked like this:

//index.php/Search/objects/search/entity_id:8744/EntityRel:100
*/
$cnt = 0; 

while ($o_items && $o_items->nextHit()) {    
  $id   = $o_items->get("ca_occurrences.occurrence_id");  
  $idno = $o_items->get("ca_occurrences.idno"); 
  $name = $o_items->get("ca_occurrences.preferred_labels");   
  $type = strToLower($o_items->getWithTemplate("^ca_occurrences.type_id")); 

  $type_id = $o_items->getWithTemplate('^ca_occurrences.tipo_exposicao');
  $made = $o_items->getWithTemplate('^ca_occurrences.madeMACUSP');
  
  
  
  if ($type === "exhibition")
  {
    $cnt += 1;   
  ?> 

    <tr>
      <td><?php print $cnt ?> </td>
  
      <td>      
        <a target='blank' href="<?php print caNavUrl($this->request, 'editor', 'occurrences', 'OccurrenceEditor/Edit/occurrence_id/'.$id); ?>"> <?php echo $id ?></a>
      </td>
      <td>
        <?php 
          print "$name" ;
        ?>
        <input type='hidden' id="idno-<?php print $cnt ?>" value="<?php print $id ?>"/>        
      </td>    
       <td>    
        <?php print $type; ?>      
      </td>  
      <td>        
        <?php print $idno; ?>      
      </td>  
      <td>    
        <?php print $type_id; ?>      
      </td>
      <td>
        <?php print "<div id='status-$cnt'> $made </div>"; ?>
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
      if (idx <= limit){
        
        var idno = document.querySelector("#idno-" + idx.toString()).value.trim();        
        var divWikicode = "#wikicode-" + idx.toString(); 
        var divStatus = "#status-" + idx.toString(); 
        
        jQuery(divStatus).html("Searching... <i class='fa fa-spinner fa-spin'></i>");

        jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'changeType'); ?>', {idno}, function(data) {    
          var total = data['results'];   
          
          jQuery(divStatus).html(total);

          roboto(idx + 1); 
        });
      
      

      }
        
    }
     
    
</script>
