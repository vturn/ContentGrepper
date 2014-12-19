<html>
<head>
<title>Form</title>
<style>
h1{
font-size: 12pt;
}
h2{
font-size: 11pt;
}
h3 {
font-size: 9pt;
margin: 0px;
padding: 0px;
}
body, div, span, textarea, input {
font-size: 9pt;
}
* {
color: #333;
font-family: 'Segoe UI, Tahoma, sans-serif';
}
input, textarea {
    border: 1px solid #999;
    padding: 3px;
}
.textbox {
    width:inherit;
    margin-bottom: 5px;
}
.red_bold {
    color: #FF0000;
    font-weight: bold;
}
#layout {
    width: 800px;
}
form, #one, #two, #three, #four, #one_url, .button, #two_itempattern, #four_preview { 
    width: inherit;
}
#one_preview, #two, #two_preview, #three, #four {
    display:none;
}
.space {
    height:30px;
    width:100%;
}
#two_preview2, #preview3{
    border: 1px solid #999;
}
.button{
    padding-top: 5px;
}
#two_itempattern, #three_feed_desc{
    height: 100px;
}
.two_feed_time{
    color: #999;
    font-style: italic;
}
.two_feed_content{
    line-height: 1.5;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" href="lib/highlight/styles/default.css">
<script src="lib/highlight/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<script>
$(document).ready(function(){
    console.log('document loaded.');
    $('#one_button').click(function(){
        console.log('preview button clicked');
        url = $('#one_url').val();
        $.get('getlinkcontent.php?a=one&u=' + url , function( data ) {
            console.log( "Load was performed(1)." );
            $('#one_preview').css('display', 'block');
            $('#two').css('display', 'block');
            $('#preview').html(data);
            $('#preview').each(function(i, block){
                hljs.highlightBlock(block);
            });
        });
    });
    $('#two_button').click(function(){
        console.log('extract button clicked');
        url = $('#one_url').val();
        pattern = $('#two_itempattern').val();
        $.get('getlinkcontent.php?a=two&u=' + url + '&p=' + pattern , function( data ) {
            console.log( "Load was performed(2)." );
            $('#two_preview').css('display', 'block');
            $('#three').css('display', 'block');
            $('#three_feed_url').val(url); 
            $('#preview2').html(data);
        });
    });
    $('#three_button').click(function(){
        console.log('preview result button clicked');
        url = $('#one_url').val();
        pattern = $('#two_itempattern').val();
        submitdata = $('#three_form').serializeArray();
        submitdata.push({name : 'url', value: url});
        submitdata.push({name : 'local', value : pattern});
console.log(submitdata);
        $.post('preview.php', submitdata).done(function(data){ 
            console.log( "Load was performed(3)." );
            $('#four_preview').css('display', 'block');
            $('#four').css('display', 'block');
            /*var iframe = document.createElement('iframe');
            iframe.id = 'preview3';
            iframe.name = 'preview3';
            iframe.src = data;
            $('#four_preview').html(iframe);*/
            $('#preview3').attr('src', data);
            $('#four_path').html('<a href="' + data + '" target="_blank">' + data + '</a>');
        });
    });


});
</script>
<style>
#preview, #preview2, #preview3 {
width:inherit;
height: 300px;
border:1px solid #999;

}
#preview2{
overflow-x : auto;
padding: 5px;
}
</style>
</head>
<body>
<div id='layout'>
<div id='one'>
<h1>Step 1. Specify source page address (URL)</h1>
<input type="text" class="textbox" id="one_url" value="http://www.dcfever.com/news/index.php?type=phones" placeholder="address starting with http:// or https://">
<div class="button"><input type="button" id="one_button" value="Preview"></div>
<div class='space'></div>
<div id='one_preview'>
<h2>Below is the HTML source of the retrieved page. Use it to setup extraction rules (see next step).</h2>
<pre><div id='preview'>
</div></pre>
</div>
<div class='space'></div>
</div>
<div id='two'>
<h1>Step 2. Define extraction rules</h1>
<h2>Item (repeatable) Search Pattern*</h2>
<textarea class="textbox" id="two_itempattern" wrap="soft"><div class="article_abstract"><h3><a href="{%}">{%}</a></h3>{*}<p>{%}</p></textarea>
<div class="button"><input id="two_button" type="button" value="Extract"></div>
<div class="space"></div>
<div id="two_preview">
<h2>Below is list of extracted text snippets ({%N}). You can reference them when setting up item properties (see next step).</h2>
<div id="preview2"></div>
</div>
<div class="space"></div>
</div>
<form id="three_form" name="three_form">
<div id='three'>
<h1>Step 3. Define output format</h1>
<h2>RSS feed properties</h2>
<input type="text" class="textbox" value="Feed Title" name="three_feed_title" id="three_feed_title" placeholder="Feed Title">
<input type="text" class="textbox" value="" name="three_feed_url" id="three_feed_url" placeholder="Feed URL">
<textarea type="text" class="textbox" name="three_feed_desc" id="three_feed_desc" placeholder="Feed Description">Feed Description</textarea>
<div class="space"></div>
<h2>RSS item properties</h2>
<h3>Put {%1}, {%2}, ... {%N} in the types below correspondingly.</h3>
<input type="text" class="textbox" value="{%1}" name="three_item_title" id="three_item_title" placeholder="Item Title, for example: {%0}">
<input type="text" class="textbox" value="{%0}" name="three_item_url" id="three_item_url" placeholder="Item URL, for example: {%1}">
<textarea type="text" class="textbox"  name="three_item_desc" id="three_item_desc" placeholder="Item content, for example: {%2}">{%2}</textarea>
<div class="button"><input id="three_button" type="button" value="Preview"></div>
<div class="space"></div>
</div>
</form>

<div id='four'>
<h2>Here is how your feed will look like in feed reader. Go to next step to get the link to your feed.</h2>
<div name="four_preview" id="four_preview"><iframe src="" id="preview3" name="preview3"></iframe></div>
<div class="space"></div>
<h2>Step 4. Get your RSS feed</h2>
<div id="four_path" name="four_path"></div>
</div>

</div>
</body>
</html>
