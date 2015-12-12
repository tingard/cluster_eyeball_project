<!DOCTYPE html>
<!-- path to x-ray /mnt/lustre/scratch/inf/ab615/work/rmv5/jpg
    SDSS information /mnt/lustre/scratch/inf/ab615/work/rmv3/sdss/ -->
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
</script>
<link rel="stylesheet" href="stylesheet.css">
<script style="text/javasript" src="js/PapaParse-4.1.2/papaparse.js"></script>
<script>

function tdWrap(s) { return '<td>' + s + '</td>'}

function makeLink(s,href) { return '<a href="'+href+'">'+s+'</a>'; }

var index = 0;
var maxIndex = 0;
var dataArray = new Array();
var changeLog = new Array();
var firstLineCheck = 0;
var options = ['None','Good','Not Sure','Bad'];
var foo;

function async(arg, your_function, callback) {
    setTimeout(function() {
        your_function(arg);
        if (callback) {callback();}
    }, 0);
}

function select_change(obs_index){
    changed_to = $('#select_'+obs_index)[0].selectedIndex;
    //console.log(obs_index+' select changed to ' + options[changed_to]);
    dataArray[obs_index][dataArray[obs_index].length-1] = changed_to;
    changeLog.push([obs_index, dataArray[obs_index]]);
}

function flagGenerator(obs_index){
    select = '<select id="select_'+obs_index+'" onchange="select_change('+obs_index+')"'+
         'style="width:100px">\n\t'+
         '<option value="0">None</option>\n\t' +
         '<option value="1">Good</option>\n\t' +
         '<option value="2">NotSure</option>\n\t' +
         '<option value="3">Bad</option>\n</select>\n';    
    return tdWrap(select);
}

Papa.parse("http://astronomy.sussex.ac.uk/~tl229/cluster_flag/id_desIm_XMMSrc_XMMObsId.csv ", {
    download:true,
    step: function(results) {
        if (firstLineCheck==0){ firstLineCheck++; return; }
        data = results.data[0];
        out = '<tr>';
        if (data.length > 1){
            flag = data[data.length-1];
            out += tdWrap(data[0]);
            out += tdWrap('<img src="'+data[1]+'" style="height:512px;width:512px;">');
            out += tdWrap('<img src="'+data[2]+'" style="height:512px;width:512px;">');
            out += tdWrap('<img src="'+data[3]+'" style="height:512px;width:512px;">');
            //for (i=0; i<2; i++){
            //out += tdWrap(data[i]);
            //}
            out += flagGenerator(index);
            out += '</tr>';
            $(out).appendTo('#testBox');
            $('select_'+index+' option[value="' + flag + '"]').attr('selected', true);
        }
        dataArray.push(data)
        index++;
    }
})
function checkOther() {
    console.log($('#nameSelect')[0].selectedIndex);
    if ($('#nameSelect')[0].selectedIndex==6){
        $('#nameInput').attr('disabled', false);
    }
    else {
        $('#nameInput').attr('disabled', true);
    }
}
var name;
function name_submitted() {
}

function getName() {
    $('select:not(#nameSelect)').attr('disabled',true);
    $('button:not(#nameButton)').attr('disabled',true);
    $('#nameBox').fadeIn();
}

function submit_changes() {
    if ($('#nameSelect')[0].selectedIndex==6){
        name = $('#nameInput').val();
    } else {
        name = ['Kathy','Phil','Alberto','Rutu'][$('#nameSelect')[0].selectedIndex];
    }
    console.log('Name: '+name);
    $('select').attr('disabled', false);
    $('button').attr('disabled', false);
    $('#nameBox').hide();
    changeLog.reverse(); // botch to replace stack method, results in un-neccesary posts
    while (changeLog.length>0){
        row = changeLog.pop()
        console.log('posting');
        postData = {};
        postData['index'] = row[0];
        postData['flag'] = row[1][row[1].length-1];
        postData['name'] = name;
        $.ajax({
            type: "POST",
            url: "./flag_handler.php",
            data: postData,
            success: function(response) {
                console.log(response);
                $('#HeadBox').html('Your changes have been sent for approval, thank you');
            }
        });
    }
    changeLog = new Array();
    name = '';
}
function cancel_submission() {
    $('select').attr('disabled', false);
    $('button').attr('disabled', false);
    $('#nameBox').hide();

}
</script>
</head>	
<body>
<!--<div id="nameInput">
Name (required): <input type='text' id='name'><br>
</div>-->
<button id='submit_flags' onclick="getName()">Submit</button>
<p id=HeadBox>Make changes and click Submit to send for approval</p>
<div id='tableWrapper'>
<table id='testBox' style='width:80%;'>
<tr><td width=75px>XMM ID<td>DES Image<td>XMM Image<td>XMM ObsId<td width=200px>Flag</tr>
</table>
</div>
<div id="nameBox" hidden>Name:<br>
<select id="nameSelect" onchange="checkOther()" style="width:100px">
    <option value="0">Kathy</option>
    <option value="1">Phil</option>
    <option value="2">Chris</option>
    <option value="3">Alberto</option>
    <option value="4">Carlos</option>
    <option value="5">Rutu</option>
    <option value="6">other</option>
</select>
<input type="text" id="nameInput" disabled>
<br>
<button id="nameButton" onclick="submit_changes()">Submit</button>
<button id="cancelButton" onclick="cancel_submission()">Cancel</button>
</div>
</body>
</html></head>
