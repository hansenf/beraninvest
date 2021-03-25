var xmlhttp;
window.onload = function(){
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", "/ajax_query/categories", true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var response = JSON.parse(xmlhttp.responseText);
            if(response.status == "bad"){
                $('body').text('Whoops! Something went wrong there.');
                return;
            }
            var $selectElement = $('select#categories');
            $selectElement.append($("<option>Everything</option>"));
            for(var index in response.rows){
                var row = response.rows[index];
                var $optionItem = $("<option value='"+ row.category_id +"'>"+ row.category_name +"</option>");
                $selectElement.append($optionItem);
            }
        }
    };

    xmlhttp.send("table=Categories&action=fetch");
};
