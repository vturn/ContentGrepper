<html>
<head>
<title>Form</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    console.log('document loaded.');
    $('#one_button').click(function(){
        console.log('preview button clicked');
        url = $('#one_url').val();
        alert(url);
        $.get(url , function( data ) {
            console.log( "Load was performed." );
        });
    });
});
</script>
<style>
#one_url {
width: 300px;
}
#preview {
width:650px;
height: 600px;
border:1px solid;
}
</style>
</head>
<body>
<div id='one'>
<h1>Select Page ...</h1>
<input type="text" id="one_url" value="">
<input type="button" id="one_button" value="Preview">
</div>
<div id='preview'>
a
</div>
</body>
</html>
