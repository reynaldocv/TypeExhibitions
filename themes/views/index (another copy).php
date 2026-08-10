<?php

$count = 1;
$o_items = $this->getVar('items');

?>

<?php print "<h3>Lista de Artistas </h3>" ?>
<div class="control-box rounded">
		<div class="control-box-left-content">
      <div class="simple-search-box">
        filter: 
        <input class="form-control" id="myFilter" type="text" placeholder="Search..">
      </div>
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
        <th>ULAN</th>
        <th></th>
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
  $wikicode = "<a href='$urlWIKI$wikicode' target='_blank'> $wikicode </a>";
  
  $ulancode = $o_items->get("ca_entities.IDcodes.ulancode"); 
  //$ulancode = "<a href='https://www.getty.edu/vow/ULANFullDisplay?find=&role=&nation=&subjectid=$ulancode'> $ulancode </a>";
  $ulancode = "<a href='$urlULAN$ulancode'  target='_blank'> $ulancode </a>";
  
  $type = $o_items->get("type_id");

  if ($type == "488")
  {
    $cnt += 1;
  ?> 

    <tr>
      <td><?php print $cnt ?> </td>
  
      <td>      
        <a href="<?php print caNavUrl($this->request, 'editor', 'entities', 'EntityEditor/Edit/entity_id/'.$id); ?>"> <?php echo $id ?></a>
      </td>
      <td>
        <?php 
          print "$name</td><td>$wikicode</td><td>$ulancode ";
        ?>
      <td width='80px'>
        <form  role="search" 
          action="<?php print caNavUrl($this->request, 'consulthor', 'Import', 'ShowProfile'); ?>">
      
          <input type="hidden" name="idno" value="<?php print $id ?>"> </input>     
          <button type="submit" class="btn-search" id="headerSearchButton"> <i class="fa fa-link"> ID search </i></button>
      
        </form>
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

  </tbody>
  </table>
  </div>
</div>
