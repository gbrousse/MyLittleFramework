$(function(){
    $('select#categorie').on('change',function(){
        window.location.replace($(this).val());
    });
    
    $('select#enginetype').on('change',function(){
        manageOptions();
    });
    manageOptions();
    
    function manageOptions(currentType){
        $("div.engineoptions").hide();
        $("div[data-enginetype='"+$('select#enginetype').val()+"']").show();
    }
});


