<?php 
    $item = $this->getVar('entity');

    //print "<h2>Profile: (".$item->get("idno").") ".$item->get("preferred_labels")."</h2>";
    //print "<h2>Country: ".$item->get("ca_entities.nationality")."</h2>";   
    
    $labels = $item->get("preferred_labels");     
    
    $caracteres_sem_acento = array(
        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
        'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T',
    );

    $labels = strtr($labels, $caracteres_sem_acento);
    
    //var_dump($item);

    //$r = new ReflectionObject($item);

    /*echo $r->getName() .' {' . implode(', ', array_map(
     function($p) use ($v) {
         $p->setAccessible(true);
         return $p->getName() .': '. $p->getValue($v);
     }, $r->getProperties())) .'}';

    foreach(array_keys($item) as $key)
      echo $key."<br>";
    */
    print "<h3> Profile: (".$item->get("idno").") ".$item->get("preferred_labels")."</h3>";
    //print "<h2> Country: ".$item->get("ca_entities.nationality")."</h2>";   
    //print "<h2> Profession -> : ".$item->get("ca_entities.dados_complementares.profissao_entidade")."</h2>";
    //print "<h2> Sex: -> ".$item->get("ca_entities.sex")."</h2>";    
    
    //print "<h2>Outros Nomes: ".$item->get("ca_entities.type_id")."</h2>";    

    //print "<h2>Datas importantes: ".$item->get("type_id")["display_text"]."</h2>";  
    //print $item->get("ca_entities.DadosBiograficos.LocalNascimento")." - ".$item->get("ca_entities.DadosBiograficos.AnoNascimento");
    //print $item->get("ca_entities.DadosBiograficos.LocalMorte")." - ".$item->get("ca_entities.DadosBiograficos.AnoMorte");

    //print "<h2>Dados complementarios: ".$item->get("type_id")["display_text"]."</h2>";    

    //print "<b>Description: </b><br>".$item->get("ca_entities.internal_notes")."<br>";    

    $wikicode = $item->get("ca_entities.IDcodes.wikicode");
    $ulancode = $item->get("ca_entities.IDcodes.ulancode");  

?>
    <input type='hidden' id='idno' value='<?php print $item->get("rank") ?>'>

    <table>
      <tr>
        <td> Wikidata code:
        </td>       
        <td> 
      
          <table>
          <tr>
            <td> <input id='wikiText' type='text' style='font-size:14px' size='30' value='<?php print $wikicode ?>' readonly>
            </td>       
            <td>
              <div style="cursor:pointer">
                <i onclick="saveData('wiki')" class='fa fa-save' style='color:green'> Save</i></div>
            </td>
          </tr>
          </table>

        </td>
        
      </tr>
      <tr>
        <td> ULAN code:
        </td>       
        
        <td>

        <table>
          <tr>
            <td> <input id='ulanText' type='text' style='font-size:14px' size='30' value='<?php print $ulancode ?>' readonly>
            </td>       
            <td>
              <div style="cursor:pointer"> 
                <i onclick="saveData('ulan')" class='fa fa-save' style='color:green'> Save</i></div>
            </td>
          </tr>
          </table>


        </td>
      </tr>
      <tr>
        <td> Save Link:
        </td>      
        <td>
        <table>
          <tr>
            <td> 
              <input id='linkLabel' type='text' style='font-size:14px' size='30' value=''>             
            </td>       
            <td>
              <div style="cursor:pointer">
                <i  onclick="saveData('link')" class='fa fa-save' style='color:green'> Add</i>
              </div>
            </td>
          </tr>
          <tr>            
            <td colspan='2'>
              <input id='linkURL' type='text' style='font-size:14px' size='70' value='' readonly><br>        
            </td>
          </tr>
          </table>
        </td>
      </tr>
    </table>

    <div style="color:red" id="status">        
    </div>    

    <div id="container-wikipedia" style="display:none;" > 

      <h3>Consulta ao Wikidata</h3>
      
      <div class="contenedor">
      <form action="#" id="formulario-wikipedia">
        <div class="control-box rounded">
          <div class="control-box-left-content">
            <div class="simple-search-box"> Search: 
              <input type="text" id="consulta-wikidata" value="<?php print $labels ?>" size="60">
            </div>
          </div>
          <div id="btn-wikidata">
            <input type="submit" value="Consultar" id="btn_consultar-wikidata">
          </div>
        </div>                  
      </form>

        <div id="resultado-wikidata">
        </div>  
      </div>  
    </div>

    <div id="container-ulan" style="display:none;"> 

      <h3>Consulta ao ULAN</h3>

      <div class="contenedor">
        <form action="#" id="formulario-ulan">
          <div class="control-box rounded">
            <div class="control-box-left-content">
              <div class="simple-search-box"> Search: 
                <input type="text" id="consulta-ulan" value="<?php print $labels ?>" size="60">
              </div>
            </div>
            <div id="btn-ulan">
              <input type="submit" value="Consultar" id="btn_consultar-ulan">
            </div>
          </div>                  
        </form> 
        <div id="resultado-ulan">
        </div>     
    </div>  

</div>  
</div>
<?php 
    #Criando o menu
    $link1 = caNavUrl($this->request, 'consulthor', 'Import', 'Index');
    $link2 = caNavUrl($this->request, 'editor', 'entities/EntityEditor/Edit/entity_id', $item->get("rank"));

    $menu = "";
    $menu .= "<br><h3><a class='sf-menu-enabled' href='$link1'><i class='fa fa-home' style='font-size: 25px;'></i> Index </a> </h3>";
    $menu .= "<hr><a class='sf-menu-enabled'  style='padding-left:30px' href='$link2' target='blank'><i class='fa fa-edit' style='font-size: 20px;'></i>Edit</a> </h2>";
    //$menu .= "<br>";

    $options = array("Wikidata"=>true, "ULAN"=>false);

    foreach ($options as $option => $value)
    {      
      $menu .= "<div  onclick=showContainer(".$value.") style='cursor:pointer;padding:10px 5px 0px 50px'>".ucfirst($option)."</div>";            
    }
    //$menu .= "<i class='caIcon fa fa-chevron-left fa-2x'></i><i class='caIcon fa fa-chevron-right fa-2x'></i>"; 


    
  ?>
  <br><br><br><br>


  <script>
    var menu = "<?php print $menu ?>";

    jQuery("#leftNavSidebar").html(menu);
    
    const formulario1 = document.querySelector("#formulario-wikipedia")
    const formulario2 = document.querySelector("#formulario-ulan")

    var ulanContainer = document.querySelector("#container-ulan");
    var wikiContainer = document.querySelector("#container-wikipedia");

    formulario1.addEventListener("submit", evento => {
      evento.preventDefault();

      const consulta = document.querySelector("#consulta-wikidata").value.trim();
      const botonConsultar = document.querySelector("#btn_consultar-wikidata");

      jQuery("#btn-wikidata").html("Searching... <i class='fa fa-spinner fa-spin'></i>");
      // Desactivar el botón y mostrar mensaje de espera
      botonConsultar.disabled = true;

      //alert("123"); 
      jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'QueryToWikidata'); ?>', {consulta}, function(data) {
			
			  var prev = "Busca realizada ( " + data['search'].length +" resultado(s) )";

        var html = "<br>";

        for(var i=0; i < data['search'].length; i++) {
          const wikicode = data['search'][i]['id']; 
          const label = data['search'][i]['label']; 
          const description = data['search'][i]['description']; 
          const aliases = data['search'][i]['aliases']; 
          const link = "https://www.wikidata.org/wiki/" + wikicode; 
					
          html += "<tr class='odd'>";
          html += "<td><a href='" + link + "'>" + wikicode + "</a></td>";
          
          html += "<td>" + label + "</td>";
          html += "<td>" + description + "</td>";
          html += "<td>" + aliases + "</td>";
          
          html += "<td style='cursor:pointer'><div syt><i onclick=myfunction('" + wikicode + "') class='fa fa-search' style='font-size:18px;color:green'></i></td>";
          html += "";
          html += "<td style='cursor:pointer'><i onclick=modifyTextBox('wiki','','" + wikicode + "') class='fa fa-copy' style='font-size:16px;color:blue'></i></td>";
          html += "<td style='cursor:pointer'><i onclick=modifyTextBox('link','Wikidata','" + link + "') class='fa fa-link' style='font-size:16px;color:gray'></i></td>";            
          html += "</tr><tr><td></td>";
          
          html += "<td colspan='6'>";
          html += "<div class='wikilinks' id='wikilink" + wikicode +"'> </div>";
          html += "</td>";
          html += "</tr>";
        }
        
        html = "<div class='container'><table class='listtable'>" + html + "</table><br><br>";
        html += "</div>";
			  jQuery("#resultado-wikidata").html(prev + html);   
        jQuery("#btn-wikidata").html("<input type='submit' value='Consultar' id='btn_consultar-wikidata'>");     
    
      })       
      
    });

    formulario2.addEventListener("submit", evento => {
      evento.preventDefault();
      //alert("ulan"); 
      const consulta = document.querySelector("#consulta-ulan").value.trim();
      const botonConsultar = document.querySelector("#btn_consultar-ulan");

      jQuery("#btn-ulan").html("Searching... <i class='fa fa-spinner fa-spin'></i>");
      // Desactivar el botón y mostrar mensaje de espera
      /*botonConsultar.disabled = true;
      botonConsultar.innerHTML = "jaja";
      botonConsultar.disabled = false;*/
      //alert("123"); 
      const words = consulta.split(' ');

      //alert(words[0]);

      jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'QueryToULAN'); ?>', {consulta}, function(data) {
        //alert("in querytoulan"); 
			  var prev = "Busca realizada: " + data.length +" resultado(s) ";

        var html = "<br>";        

        for(var i=0; i < data.length; i++) {
          const ulancode = i; 
          
          const label = data[i]['Term']['value'];           
          var _continue = true; 
          
          //const link = data[i]['Type']["html"]; 
          //const description = data[i]['search'][i]['description']; 
          //const aliases = data[i]['search'][i]['aliases']; 

          //const words = consulta.split(' ');
       
          if (_continue == true)
          {
            const link = data[i]['Subject']["value"]; 
            const ulancode = link.replace("http://vocab.getty.edu/ulan/", "");
            const link2 = "http://vocab.getty.edu/page/ulan/" + ulancode; 
            const parents = data[i]['Parents']["value"];
            const bio = data[i]['bio']["value"];
            
            html += "<tr class='odd'>";
            html += "<td width='8%'><a href='" + link2 + "' target='_blank'>" + ulancode + "</a></td>";
            
            html += "<td width='15%'>" + label + "</td>";
            html += "<td width='15%'>" + parents + "</td>";
            html += "<td width='50%'>" + bio + "</td>";
            
            //html += "<td style='cursor:pointer'><i onclick=myfunction('" + ulancode + "') class='fa fa-search' style='font-size:18px;color:green'></i><td>";
            html += "";
            html += "<td style='cursor:pointer'><i onclick=modifyTextBox('ulan','','" + ulancode + "') class='fa fa-copy' style='font-size:16px;color:blue'></i></td>";
            html += "<td style='cursor:pointer'><i onclick=modifyTextBox('link','ULAN','" + link2 + "') class='fa fa-link' style='font-size:16px;color:gray'></i></td>";
            html += "</tr><tr><td></td>";
            
            html += "<td colspan='5'>";
            html += "<div class='wikilinks' id='wikilink" + ulancode +"'> </div>";
            html += "</td>";
            html += "</tr>";
          }
        }
        
        html = "<div class='container'><table class='listtable'>" + html + "</table><br><br>";
        html += "</div>";
			  jQuery("#resultado-ulan").html(prev + html);   
        jQuery("#btn-ulan").html("<input type='submit' value='Consultar' id='btn_consultar-ulan'>");         
    
      })
    });


    function myfunction(input){    
      
      const consulta = input; 
        
      jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'WikipediaLinks'); ?>', {consulta}, function(data){
        const divwiki = "#wikilink" + input;
        
        var tmp = "";

        for(var key in data) {
          tmp += "<li> <i onclick=modifyTextBox('link','Wikipedia&nbsp("+ key +")','" + data[key]["url"] +"') class='fa fa-link' style='font-size:18px;color:blue'></i>   " + key + " -> <a href='"+ data[key]["url"] + "' target='_blank'>" + data[key]["title"] + "</a>";
          tmp += "</li>";
        }
        tmp = "<ul>" + tmp + "</ul>";

        jQuery(divwiki).html(tmp);
      });
    }

    function showContainer(input){    
      if (input == true){
        ulanContainer.style.display= 'none'; 
        wikiContainer.style.display= 'block';
      }
      else{
        ulanContainer.style.display= 'block'; 
        wikiContainer.style.display= 'none';
      }
      
    }
    
    function modifyTextBox(type, label, input){ 
      //alert("okas"); 

      var attribute;  

      if (type == 'wiki'){
        attribute = document.getElementById("wikiText");  
      }

      if (type == 'ulan'){
        attribute = document.getElementById("ulanText");  
      }
      
      if (type == "link"){
        attribute = document.getElementById("linkURL");  

        linkLabel = document.getElementById("linkLabel"); 
        linkLabel.value = label; 
      }
      attribute.value = input; 
    
    }    

    function saveData(type){ 
      const idno = document.getElementById("idno").value; 
      
      //alert(type + " "+ idno);  
      
      var label = ""; 
      var value = ""; 
      
      if (type == 'link'){
        label = document.getElementById("linkLabel").value; 
        value = document.getElementById("linkURL").value;    
      }
      else
      {
        label = document.getElementById("wikiText").value; 
        value = document.getElementById("ulanText").value; 
      
      }      
      
      jQuery.getJSON('<?php print caNavUrl($this->request, '*', '*', 'SaveData'); ?>', {idno, type, label, value}, function(data){
        var html = data["results"];
        //alert("Yeah!!!" + html);
        jQuery("#status").html(html);

      });
    }
 
  </script>