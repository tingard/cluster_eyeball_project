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
            out += tdWrap('<img src="'+data[1]+'" style="height:256px;width:256px;">');
            out += tdWrap('<img src="'+data[2]+'" style="height:256px;width:256px;">');
            out += tdWrap('<img src="'+data[3]+'" style="height:256px;width:256px;">');
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
function checkother() {
    if ($('#nameSelect').selectedIndex==4){
        $('#nameInput').attr('disabled', 'false');
    }
    else {
        $('#nameInput').attr('disabled', 'true');
}
function getName() {
    $('select').attr('disabled','true');
    $('button').attr('disabled','true');
    nameSelect = '<select id="nameSelect"'+
                 'onchange="
                 'style="width:100px">\n\t'+
                 '<option value="0">Kathy</option>\n\t' +
                 '<option value="1">Phil</option>\n\t' +
                 '<option value="2">Alberto</option>\n\t' +
                 '<option value="3">Rutu</option>\n</select>\n';   
    otherBox = '<input type="text" id="nameInput">';
    nameButton = '<button id="nameButton" onclick="name_submitted()">Submit</button>'
    out = $('<div id="nameBox" disabled=true>Name:<br>' + nameSelect + "<br>" + otherBox + nameButton +
        '</div>').hide().appendTo('body').fadeIn();
}

function submit_changes() {
    getName();
    changeLog.reverse(); // botch to replace stack method, results in un-neccesary posts
    while (changeLog.length>0){
        row = changeLog.pop()
        console.log('posting');
        postData = {};
        postData['index'] = row[0];
        postData['flag'] = row[1][row[1].length-1];
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
}

</script>
</head>	
<body>
<!--<div id="nameInput">
Name (required): <input type='text' id='name'><br>
</div>-->
<button id='submit_flags' onclick="submit_changes()">Submit</button>
<p id=HeadBox>Make changes and click Submit to send for approval</p>
<div id='tableWrapper'>
<table id='testBox' style='width:80%;'>
<tr><td width=75px>XMM ID<td>DES Image<td>XMM Image<td>XMM ObsId<td width=200px>Flag</tr>
</table>
</div>
</body>
</html></head>
