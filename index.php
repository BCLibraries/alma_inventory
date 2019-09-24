<?php
if(!isset($_SESSION))
    {
        session_start();
    }
    $_SESSION['progress']=0;
    session_write_close();
//require("login.php");
require("key.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Alma Shelf Inventory</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init()
})  
</script>
<!-- start lookup Ajax js -->
<script type="text/javascript">
  function AjaxFunction() {
    var httpxml;
    try {
    // Firefox, Opera 8.0+, Safari
      httpxml=new XMLHttpRequest();
    }
    catch (e) {
    // Internet Explorer
        try {
          httpxml=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
              httpxml=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
              alert("Your browser does not support AJAX!");
              return false;
            }
        }
    }
  function stateck() {
      if (httpxml.readyState==4) {
        //alert(httpxml.responseText);
        var myarray = JSON.parse(httpxml.responseText);
        // Remove the options from 2nd dropdown list
        for (j=document.ShelfLister.location.options.length-1;j>=0;j--) {
          document.ShelfLister.location.remove(j);
        }
        for (i=0;i<myarray.locationData.length;i++) {
          var optn = document.createElement("OPTION");
          optn.text = myarray.locationData[i].name;
          optn.value = myarray.locationData[i].code;
          document.ShelfLister.location.options.add(optn);
        }
      }
  } // end of function stateck
  var url="almaLocationsAPI.php";
  var cat_id=document.getElementById('library').value;
  url=url+"?lib_id="+cat_id;
  url=url+"&sid="+Math.random();
  httpxml.onreadystatechange=stateck;
  //alert(url);
  httpxml.open("GET",url,true);
  httpxml.send(null);
}
</script>  
<!-- end location lookup Ajax js -->
</head>
<body>
    <div class="container">
        <h1>Alma Shelf Inventory</h1>
        <p class="text-muted">Fill in form and submit</p>
        <form method="post" name="ShelfLister" id="ShelfLister" action="/shelfinventory/process_barcodes.php" enctype="multipart/form-data" class="ui-widget">
    		<div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" name="file" accept=".xlsx">
                    <label class="custom-file-label" for="file">Choose file</label>
                </div>
    		</div>
    		<fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0" for="cnType">Call Number</legend>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cnType" id="cnType" value="lc" checked>
                            <label class="form-check-label" for="cnType">LC</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cnType" id="cnType" value="other">
                            <label class="form-check-label" for="cnType">Other</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="form-group row">
                <label for="library" class="col-sm-4 col-form-label">Library</label>
                <div class="selector col-sm-8" id="uniform-library">
                    <select class="custom-select" name="library" id="library" class="required" data-uniformed="true" onchange="AjaxFunction();">
                        <option selected>Select Library</option>
                        <option value="BAPST">Bapst</option>
                        <option value="BURNS">Burns</option>
                        <option value="ARCH">Burns - Archives</option>
                        <option value="DEV">Devlin</option>
                        <option value="ERC">Educational Resource Center</option>
                        <option value="INT">Internet</option>
                        <option value="LAW">Law</option>
                        <option value="ONL">O'Neill</option>
                        <option value="RES_SHARE">O'Neill - Resource Sharing Library</option>
                        <option value="MICRO">O'Neill Microforms</option>
                        <option value="SWK">Social Work Library</option>
                        <option value="TML">Theology and Ministry Library</option>
                        <option value="GEO">Weston Geophysics</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="location" class="col-sm-4 col-form-label">Scan Location</label>
                <div class="selector col-sm-8" id="uniform-location">
                    <select class="custom-select" name="location" id="location" class="required" data-uniformed="true"></select>
                </div>
            </div>
    		<div class="form-group row">
                <label for="itemType" class="col-sm-4 col-form-label">Primary Item Type for Scanned Location</label>
                <div class="selector col-sm-8" id="uniform-itemType">
                    <select class="custom-select" name="itemType" id="itemType" class="required" data-uniformed="true">
                        <option selected>Select Item Type</option>
                        <option value="BOOK">Book</option>
                        <option value="PERIODICAL">Periodical</option>
                        <option value="DVD">DVD</option>
                        <option value="THESIS">Thesis</option>
                    </select>
                </div>
            </div>
    		<fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0" for="onlyOrder">Only Report Call Number Order Problems?</legend>
                    <div class="col-sm-8">
                        <div class="form-check" id="uniform-onlyOrder">
                            <input class="form-check-input" type="radio" id="onlyOrder" name="onlyorder" value="false" checked="checked">
                            <label class="form-check-label" for="onlyOrder">No</label>
                        </div>
                        <div class="form-check" id="uniform-onlyOrder">
                            <input class="form-check-input" type="radio" id="onlyOrder" name="onlyorder" value="true">
                            <label class="form-check-label" for="onlyOrder">Yes</label>
                        </div>
                    </div>
                </div>
            </fieldset>	
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0" for="onlyOther">Only Report Problems Other Than Call Number?</legend>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="onlyOrder" name="onlyother" value="false" checked="checked">
                            <label class="form-check-label" for="onlyOrder">No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="onlyOrder" name="onlyother" value="true">
                            <label class="form-check-label" for="onlyOrder">Yes</label>
                        </div>
                    </div>
                </div>
            </fieldset>     	       
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0" for="onlyProblems">Only Report Problems?</legend>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="onlyProblems" name="onlyproblems" value="false" checked="checked">
                            <label class="form-check-label" for="onlyOrder">No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="onlyProblems" name="onlyproblems" value="true">
                            <label class="form-check-label" for="onlyOrder">Yes</label>
                        </div>
                    </div>
                </div>
            </fieldset>    
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0" for="cnType">Clear Cache?</legend>
                    <div class="col-sm-8">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="clearCache" name="clearCache" value="false" checked="checked">
                            <label class="form-check-label" for="clearCache">No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="clearCache" name="clearCache" value="true">
                            <label class="form-check-label" for="clearCache">Yes</label>
                        </div>
                    </div>
                </div>
            </fieldset>    
            <button class="btn btn-primary" type="submit" name="submit">Submit form</button>
    	</form>
    </div>
</body>