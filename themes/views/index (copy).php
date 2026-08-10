<form class="navbar-form navbar-right" role="search" 
  action="<?php print caNavUrl($this->request, 'consulthor', 'Query', 'Index'); ?>">

  <div class="formOutline">
    <div class="form-group">
      <input type="text" class="form-control" id="headerSearchInput" placeholder="Search" name="search" autocomplete="off"
      value="" />
    </div>
    <button type="submit" class="btn-search" id="headerSearchButton"><span class="glyphicon glyphicon-search"></span></button>
  </div>
</form>

<form class="navbar-form navbar-right" role="search" 
  action="<?php print caNavUrl($this->request, 'consulthor', 'Query', 'Service'); ?>">

  <div class="formOutline">
    <div class="form-group">
      <input type="text" class="form-control" id="headerSearchInput" placeholder="Search" name="search" autocomplete="off"
      value="" />
    </div>
    <button type="submit" class="btn-search" id="headerSearchButton">
      <span class="glyphicon glyphicon-search"></span> service</button>
  </div>
</form>

<?php
$colors = array("black", "white", "grey");
print_r($colors);
?>

<br>

Value : <?php print $this->getVar('search'); ?><br>
Value : <?php print $this->getVar('search1'); ?><br>
Value : <?php print $this->getVar('object'); ?><br>
Value : <?php print var_dump($this->getVar('object')); ?><br><br>
<?php
print $this->getVar('object');

print "<h1>".$this->getVar('idno');."</h1>";

print "<h1>".Session::getVar('search');."</h1>";

$count = 1;

$qr_results = $this->getVar('list');


while($qr_results->nextHit()) {
  print "<br><br> items <br><br>";
  //print_r($qr_results);
  print "<br><br><br><br>";
  $item = $qr_results->get("ca_objects.idno"); 
  $id = $qr_results->get("ca_objects.object_id"); 
  $itemLabel = $qr_results->get("ca_objects.preferred_labels"); 
  $media = $qr_results->get("ca_object_representations.media.thumbnail"); 
  $count = $qr_results->get("ca_entities_hierarchy_browser_display_settings"); 
  
  $entities = $qr_results->get('ca_entities.related', 
  array('template' => '<l>^ca_entities.related', 'delimiter'=>'<br>'));
  
  $entities2 = $qr_results->get('ca_entities.preferred_labels', 
  array('template' => '<unit relativeTo="ce_entities"><l>^ca_entities.preferred_labels<l></unit>', 'delimiter'=>'<br>'));
  $prefix = $qr_results->get('ca_entities.name.prefix'); 

  print "<br> prefix ".$prefix."<br>";
  print $media." ".$id." - ".$item." - ".$itemLabel."<br>";
  print "- >".$entities."<br>";
  print "-->".$entities2."<br>";
  print "list : ".$vm_ret."(relations )<br>";
  print "count: ".$count."<br>";
  print "->".($entities);
  print "<br><br><br><br>";
}
/*while($qr_results->nextHit()) {
    print "Hit ".$count.": ".$qr_results->get('ca_objects.preferred_labels.name')."<br/>\n";
    $count++;
}*/
?>
