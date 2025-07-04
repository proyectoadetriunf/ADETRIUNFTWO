$(document).ready(function(){
    $('#Loginusuario').on('click',function(){
        Loginusuario();
    });

    $('#Loginjefedepto').on('click',function(){
        Loginjefedepto();
    });

})

function Loginusuario(){
    var login = $('#usuario').val();
    var pass = $('#pass').val();  
    
    $.ajax({
        url: './includes/loginusuario.php',
        method: 'POST' ,
        data: {
            login:login,
            pass:pass
        },

        success: function(data){
            $('#messageUsuario').html(data);

            if(data.indexof('redirecting')>=0){
                window.location = 'administrador/';
            }
        }

    })
    
}


function Loginjefedepto(){
    var login = $('#usuario').val();
    var pass = $('#pass').val();  
    
    $.ajax({
        url: './includes/Loginjefedepto.php',
        method: 'POST' ,
        data: {
            login:login,
            pass:pass
        },

        success: function(data){
            $('#messagejefedepto').html(data);

            if(data.indexof('redirecting')>=0){
                window.location = 'jefedepto/';
            }
        }

    })
    
}