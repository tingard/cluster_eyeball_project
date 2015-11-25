<!DOCTYPE html>
<!-- path to x-ray /mnt/lustre/scratch/inf/ab615/work/rmv5/jpg
    SDSS information /mnt/lustre/scratch/inf/ab615/work/rmv3/sdss/ -->
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
</script>
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
        }
        dataArray.push(data)
        index++;
    }
})

function submit_changes() {
    changeLog.reverse(); // botch to replace stack method, results in un-neccesary posts
    while (changeLog.length>0){
        row = changeLog.pop()
        postData = {};
        postData['index'] = row[0];
        postData['flag'] = row[1][row[1].length-1];
        $.ajax({
            type: "POST",
            url: "./flag_handler.php",
            data: postData,
            success: function(response) {
                console.log(response);
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
<button id='submit_flags' onclick="submit_changes()">'Submit'</button>
<div id='tableWrapper'>
<table id='testBox' style='width:80%;'>
<tr><td width=75px>XMM ID<td>DES Image<td>XMM Image<td>XMM ObsId<td width=200px>Flag<td>Checkbox</tr>
</table>
</div>
</body>
</html></head>
