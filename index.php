<!DOCTYPE html>
<!-- path to x-ray /mnt/lustre/scratch/inf/ab615/work/rmv5/jpg
	SDSS information /mnt/lustre/scratch/inf/ab615/work/rmv3/sdss/ -->
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
</script>
<script style="text/javasript" src="js/PapaParse-4.1.2/papaparse.js"></script>
<script>
function cleanup(s) { return s.replace(/\\/g,'').replace(/"/g,'') }

function tdWrap(s) { return '<td>' + s + '</td>'}

function makeLink(s,href) { return '<a href="'+href+'">'+s+'</a>'; }

var index = 0;
var maxIndex = 0;
var dataArray = new Array();

var foo;
Papa.parse("http://astronomy.sussex.ac.uk/~tl229/astro_hack_day/cart_WP8_photoz_XMM_LSS.csv", {
	worker:true,
	download: true,
	step: function(results) {
		console.log(results.data);
		async(foo, generateTableRow);
	}
})

function generateTableRow(data) {
	out = '';
	for (i=0; i<data.length; i++){
		out += tdWrap(data[i]);
	}
	out.hide().appendTo('#testBox').fadeIn(); 
}

/*
function buttonBind() {
	$('button').click(function(){
		if (flagEvent1(this.id)){
			$(this).prop('disabled','disabled');
		}
	});
}
var sourecListRetrieve = new XMLHttpRequest(); //New request object
sourecListRetrieve.onload = function() {
	var file_contents = this.responseText.split('<--FLAG_SECTION-->');
	var csv_contents = file_contents[0];
	var tmp_array = csv_contents.split('\\n');
	for (i=0; i<tmp_array.length; i++){
		dataArray.push(tmp_array[i].split(','))
	}
	
	// TODO: add in code to get flagged data here
	

	maxIndex = tmp_array.length;
	var count =0;
	while($(window).scrollTop() >= $(document).height() - 
			$(window).height() - 50) {
    	if (index < maxIndex) {
    		// TODO: change background if flagged
			$(getNextObservation()).hide().appendTo('#testBox').fadeIn(); 
			index++;
		}
		count++;
		if(count>100){ break };
	}
	buttonBind();
};
sourecListRetrieve.open("get", "./bin/getData.php", true);
sourecListRetrieve.send();
function flagEvent1(id) {
	console.log(id);
	name = document.getElementById('name').value;
	textThing = document.getElementById(id.split('_FLAG')[0]+'_txt').value;
	if (name.length==0) {
		alert('Please input a name');
		return false;
	} else {
		postData = {};
		postData['id'] = id
		postData['name']=name;	
		if (textThing!=undefined) {
			postData['foo'] = textThing
		}
		$.ajax({
			type: "POST",
			url: "./bin/flag.php",
			data: postData,
			success: function() {
				console.log('Sent form');
			}	
  		});
		return true
	}
}
function getNextObservation() {
	name = cleanup(dataArray[index][0]);
	redshift = 'Redshift: '+cleanup(dataArray[index][1]);
	temperature = 'Temperature: '+cleanup(dataArray[index][2]);
	richness = 'Richness: '+cleanup(dataArray[index][22]);
	infoDiv = '<div>'+Array(name,redshift,temperature,richness).join('<br>')
		+'</div>';
	matchID = dataArray[index][19];
	obsID_1 = dataArray[index][26];
	obsID_2 = dataArray[index][26];
	while (obsID_1.length<10){
		obsID_1 = '0'+obsID_1;
	}
	src1 = './albertoIm/'+matchID+'_'+obsID_1+'_z.jpg';
	src2 = "./sdss/"+matchID+'_'+obsID_2+"_s.jpg";
	image1 = makeLink('<img src="'+src1+'">', src1);
	image2 = makeLink('<img src="'+src2+'">', src2);
	
	// TODO: more flagging options
	flag = dataArray[index][dataArray[index].length-1].replace('\\','');
	button1 = '<button id="'+name.replace(' ','_')+'_FLAG1" ';
	button2 = '<button id="'+name.replace(' ','_')+'_FLAG2" ';
	if (flag==1 || flag==3){
		button1 += 'disabled="disabled" ';
	}
	if (flag==2 || flag==3) {
		button2 += 'disabled="disabled" ';
	}
	button1 += '>Flag1</button>'
	button2 += '>Flag2</button>'
	thingyInput = '<input type="text" id="'+name.replace(' ','_')+'_txt">';
	buttonDiv = '<div class="FLAG">'+ button1 + '<br>' + 
		button2 + '<br>' + thingyInput + '</div>';
	return '<tr>'+tdWrap(infoDiv)+tdWrap(image1)+tdWrap(image2)+
		tdWrap(buttonDiv)+'</tr>';
}
$(window).scroll(function() {
	if($(window).scrollTop() > $(document).height() - $(window).height() -50) {
	    if (index < maxIndex) {
			$('button').unbind();
			$(getNextObservation()).hide().appendTo('#testBox').fadeIn(); 
			buttonBind();
			index++;
		} else{
			console.log("can't scroll: ".concat(index,' ',maxIndex));
		}
	}
});
$(window).resize(function() {
	if($(window).scrollTop() > $(document).height() - $(window).height() -50) {
	    if (index < maxIndex) {
			$('button').unbind();
			$(getNextObservation()).hide().appendTo('#testBox').fadeIn(); 
			buttonBind();
			index++;
		} else{
			console.log("can't scroll: ".concat(index,' ',maxIndex));
		}
	}
});
*/

</script>
</head>	
<body>
<div id="nameInput">
Name (required): <input type='text' id='name'><br>
</div>
<div id='tableWrapper'>
<table id='testBox'>
<tr><td>Information<td>XCS image<td>SDSS image<td>Flag cluster</tr>
</table>
</div>
</body>
</html></head>

