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
var foo;
index = 0;

function async(arg, your_function, callback) {
	setTimeout(function() {
		your_function(arg);
		if (callback) {callback();}
	}, 0);
}

function select_change(obs_index){
	changed_to = $('#select_'+obs_index)[0].selectedIndex;
	console.log(obs_index+' select changed to index ' + changed_to);
	console.log(dataArray[obs_index][2+changed_to]);
	$('#check_'+obs_index)[0].checked = dataArray[obs_index][2+changed_to]== "1" ? true : false;
}
function checkbox_click(obs_index){
	console.log(obs_index+' checkbox changed');
	current_flag = $('#select_'+obs_index)[0].selectedIndex;
	console.log("Current flag: "+current_flag);
	changed_listing = dataArray[obs_index];
	changed_listing[2+current_flag] = $('#check_'+obs_index)[0].checked ? "1" : "0";
	changeLog.push([obs_index, changed_listing]);
	dataArray[obs_index] = changed_listing;
	console.log(current_flag);
}

function flagGenerator(obs_index){
	select = '<select id="select_'+obs_index+'" onchange="select_change('+obs_index+')"'+
			 'style="width:100px">\n\t'+
		     '<option value="0">Flag0</option>\n\t' +
		     //'<option value="1">Flag1</option>\n\t' +
		     //'<option value="2">Flag2</option>\n\t' +
   		     '<option value="3">Flag1</option>\n</select>\n';
	checkbox = '<input id="check_'+ obs_index +'" type="checkbox" onclick="checkbox_click('+
			   obs_index+')" name="isFlag">';
	return tdWrap(select)+tdWrap(checkbox);
}
var steps = 0;
Papa.parse("http://astronomy.sussex.ac.uk/~tl229/cluster_flag/id_desIm_XMMSrc_XMMObsId.csv ", {
	download:true,
	step: function(results) {
		if (index==0){ return; }
		data = results.data[0];
		out = '<tr>';
		if (data.length > 1){
			for (i=0; i<2; i++){
				out += tdWrap(data[i]);
			}
			out += flagGenerator(index);
			out += '</tr>';
			$(out).appendTo('#testBox');
		}
		dataArray.push(data)
		index++;
	}
})

function submit_changes() {
	indexList = new Array();
	changeLog.reverse(); // botch to replace stack method, results in un-neccesary posts
	while (changeLog.length>0){
		row = changeLog.pop(0)
		if (indexList.indexOf(row[0])<0){
			postData = {};
			postData['index'] = row[0];
			postData['data'] = row[1];
			console.log("Changed data:");
			console.log(postData);
		}
			//indexList.push(row[0]);
		/*
		$.ajax({
			type: "POST",
			url: "./bin/changeFlags.php",
			data: postData,
			success: function() {
				console.log('Sent form');
			}	
  		});*/
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
<tr><td width=75px>number<td>letter<td width=200px>Flag<td>Checkbox</tr>
</table>
</div>
</body>
</html></head>

