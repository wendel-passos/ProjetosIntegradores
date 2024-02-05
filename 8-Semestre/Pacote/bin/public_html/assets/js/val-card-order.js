// ********************************************************************
// VALIDA FORM DE CADASTRAR PEDIDO

function valCadOrder() {
    var quantidade = $('#quantidade').val();

    function resInputError(id, tipo) {

        if (tipo === "") {
            $("#" + id).addClass("error-input-border");
            $("#" + id).after("<p class='error-input-text'>Este campo é obrigatório!</p>");

        } else if (tipo === "somenteNumeros") {
            $("#" + id).addClass("error-input-border");
            $("#" + id).after("<p class='error-input-text'>A Quantidade deve ser maior que 0!</p>");
        }

        setTimeout(function () {
            $("#" + id).removeClass("error-input-border");
            $(".error-input-text").hide('slow');
        }, 3000);
    }

    // VALIDAR AQUI SE OS CAMPOS ESTÃO PREENCHIDOS
    if (!quantidade) {
        resInputError("quantidade", "");
        return false;

    } else if (quantidade <= 0) {
        resInputError("quantidade", "somenteNumeros");
        return false;
    }

    return true;
};


