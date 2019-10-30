    // atributos para el paginador
    var paginador;
    var totalPaginas;
    var itemsPorPagina = 2;
    var numerosPorPagina = 2;
    var desde = 0;
    var pagina = 0;

// inicar el paginador recibiendo el elemento html, cantidades de items y numeros por pag
function init_paginator(paginador,model_itemsPorPagina,model_numerosPorPagina){
  paginador = paginador;
  itemsPorPagina = model_itemsPorPagina;
  numerosPorPagina = model_numerosPorPagina;
}

// crear el paginador a partir de la cantidad de registros
function creaPaginador(totalItems)
{ 
  // limpira el componente 
  paginador.html("");
  totalPaginas = Math.ceil(totalItems/itemsPorPagina);
  var actual =0;        



  $('<li class="waves-effect" ><a  href="#!"><i id="prev_link" class="material-icons">chevron_left</i></a></li>').appendTo(paginador);

  var pag=1;
  while(totalPaginas>=pag){
    $('<li  class="waves-effect numbers"><a >'+pag+'</a></li>').appendTo(paginador);
    pag++;
  }
  if(numerosPorPagina > 1){
    $(".numbers").hide();
    $(".numbers").slice(0,numerosPorPagina).show();
  }

  $('<li class="waves-effect" ><a href="#!"><i id="next_link"  class="material-icons">chevron_right</i></a></li>').appendTo(paginador);
  
  if(pagina==0){
    paginador.find(".waves-effect:first").addClass("active");
    paginador.find(".waves-effect:first").parents("li").addClass("active");

  }

  paginador.find("#prev_link").click(function()
  {

    temp= actual-1;
    actual=temp;

    cargaPagina(temp);          

    return false;
  });

  paginador.find("#next_link").click(function()
  {
          //var irpagina =parseInt(paginador.data("pag")) -1;
          //cargaPagina(irpagina);
          temp= actual+1;
          actual=temp;
          cargaPagina(temp);   

          return false;
        });

  paginador.find(".waves-effect a").click(function(){
   var irPagina=($(this).html());
   cargaPagina(irPagina-1);
   actual = irPagina-1;
   return false;

 });
}
// funcion get_data donde alamacenara la paticion ajax
function get_data(){
  // todo dentro de esta funcion sera remplazado por la peticion ajax
  console.log("nada");
}

// funcion que recibe la peticion ajax como callback 
function set_callback(callback){
  (function (w){
      // actualiza la funcion, ahora ejecutara la peticon ajax del callback  
      w.get_data = function (){
        callback();
      }
    })(window || {});
  }

  function cargaPagina(pag)
  {
    pagina=pag;
  // calcula el offset
  desde = pagina * itemsPorPagina;
  // get_data contiene y ejecuta la peticion ajax enviada como callback 
  get_data();

  if(pagina >= 1)
  {
    paginador.find("#prev_link").show();
  }
  else
  {
    paginador.find("#prev_link").hide();
  }

  if(pagina <(totalPaginas- numerosPorPagina))
  {
    paginador.find("#next_link").show();
  }else
  {
    paginador.find("#next_link").hide();
  }
  // obtiene la pagina selecionada
  paginador.data("pag",pagina);

  if(numerosPorPagina>1)
  {
    $(".numbers").hide();
    if(pagina < (totalPaginas- numerosPorPagina))
    {
      $(".numbers").slice(pagina,numerosPorPagina + pagina).show();
    }
    else{
      if(totalPaginas > numerosPorPagina)
        $(".numbers").slice(totalPaginas- numerosPorPagina).show();
      else
        $(".numbers").slice(1).show();
    }
  }
  paginador.children().removeClass("active");
  paginador.children().eq(pagina+1).addClass("active");
  
}