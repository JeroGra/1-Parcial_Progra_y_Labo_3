namespace PrimerParcial
{    
    /// <reference path="./clases.ts" />
    /// <reference path="./interfaces.ts" />
    const xhttp : XMLHttpRequest = new XMLHttpRequest();
    const formData : FormData = new FormData();
    export class Manejadora implements IParte2, IParte3
    {
        static LimpiarNeumaticos(){

            (<HTMLInputElement>document.getElementById("marca")).value = "";
            (<HTMLInputElement>document.getElementById("medidas")).value = "";
            (<HTMLInputElement>document.getElementById("precio")).value = "";
        }
        
        
        static LimpiarFoto(){
            
            (<HTMLInputElement> document.getElementById("foto")).value = "";
            (<HTMLInputElement> document.getElementById("imgFoto")).src = "./neumatico.defaul.jfif";
            
        }
        
        static LimpiarNeumaticosBdHTML()
        {
            (<HTMLInputElement>document.getElementById("idNeumatico")).value = "";
            if( (<HTMLInputElement> document.getElementById("foto")))
            {

                this.LimpiarFoto();
            }
            this.LimpiarNeumaticos();
        
        }

    static  BorrarCampos()
    {
        this.LimpiarNeumaticosBdHTML();
    }

    static  RespuestaJSON()
    {
       xhttp.onreadystatechange = () => {
          if (xhttp.readyState == 4 && xhttp.status == 200) {
             let jsonMsj = JSON.parse(xhttp.responseText); 
             console.log(xhttp.responseText);
             alert(jsonMsj.mensaje);
             if(jsonMsj.exito)
             {
                this.MostrarNeumaticosJSON();
             }
          }
      };
    }

    static  RespuestaNeumaticosBDJSON()
    {
       xhttp.onreadystatechange = () => {
          if (xhttp.readyState == 4 && xhttp.status == 200) {
             let jsonMsj = JSON.parse(xhttp.responseText); 
             console.log(xhttp.responseText);
             alert(jsonMsj.mensaje);
             if(jsonMsj.exito)
             {
                this.MostrarNeumaticosBD();
             }
          }
      };
    }

    static  AgregarNeumaticoJSON()
    {
        let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
        let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
        let precio : string = (<HTMLInputElement> document.getElementById("precio")).value;

        xhttp.open('POST', './backend/altaNeumaticoJSON.php', true);

        formData.append('marca', marca);
        formData.append('medidas', medidas);
        formData.append('precio', precio);

        xhttp.send(formData);

        this.RespuestaJSON();
        this.LimpiarNeumaticos();
    }

    static   MostrarNeumaticosJSON()
    {
       xhttp.open('GET','./backend/listadoNeumaticosJSON.php', true)

       xhttp.send();

       xhttp.onreadystatechange = () => 
       {
        
           let div = <HTMLDivElement>document.getElementById("divTabla");

           let tabla = `<table>
           <tr>
                 <th>MARCA</th><th>MEDIDAS</th><th>PRECIO</th>
           </tr>`;
           if (xhttp.readyState == 4 && xhttp.status == 200) 
           {

              let jsonMsj =  JSON.parse(xhttp.responseText);

              for(let i = 0;i<jsonMsj.length;i++)
              {
                 let dato = jsonMsj[i];
                 tabla += `<tr>
                                  <th>${dato.marca}</th><th>${dato.medidas}</th><th>${dato.precio}</th>
                           </tr>
                 `
              }
           }
           
           tabla += `</table>`;
           div.innerHTML = tabla;
       };

    }

    static  VerificarNeumaticoJSON()
    {
        let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
        let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;

        formData.append('marca',marca);
        formData.append('medidas',medidas);
     
        xhttp.open('POST','./backend/VerificarNeumaticoJSON.php', true)

        xhttp.send(formData);

        this.RespuestaJSON();
        this.LimpiarNeumaticos();
    }

     ///Neumaticos con Base de datos


    //Neumaticos sin Fotos


    static AgregarNeumaticoSinFoto()
        {
         let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
         let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
         let precio : Number = parseInt((<HTMLInputElement> document.getElementById("precio")).value);

         let neumatico = new Entidades.Neumatico(marca,medidas,precio);

         xhttp.open('POST', './backend/agregarNeumaticoSinFoto.php', true);

         formData.append('neumatico_json', JSON.stringify(neumatico));

         xhttp.send(formData);

         this.RespuestaNeumaticosBDJSON();
         this.LimpiarNeumaticosBdHTML();
        }

        static  MostrarNeumaticosBD()
        {
            xhttp.open('GET','./backend/listadoNeumaticosBD.php', true)

            xhttp.send();
   
            xhttp.onreadystatechange = () => 
            {
             
               let div = <HTMLDivElement>document.getElementById("divTabla");
   
               let tabla = `<table>
               <tr>
                      <th>ID</th><th>MARCA</th><th>MEDIDAS</th><th>PRECIO</th><th>FOTO</th><th>ACCION</th>
               </tr>`;
               if (xhttp.readyState == 4 && xhttp.status == 200) 
               {
   
                    let jsonMsj =  JSON.parse(xhttp.responseText);
                    for(let i = 0;i<jsonMsj.length;i++)
                    {
                        let dato = jsonMsj[i];

                        if(dato.pathFoto == "" ||dato.pathFoto == null )
                        {
                            tabla += `<tr>
                            <th>${dato.id}</th><th>${dato.marca}</th><th>${dato.medidas}</th><th>${dato.precio}</th><th><input type="button" id="" data-obj='${JSON.stringify(dato)}'value="Seleccionar" name="btnSeleccionar"></th>
                            </tr>
                            `
                        }
                        else
                        {
                                tabla += `<tr>
                                                <th>${dato.id}</th><th>${dato.marca}</th><th>${dato.medidas}</th><th>${dato.precio}</th><th> <img src=${dato.pathFoto} width=50 height=50 /></th><th><input type="button" id="" data-obj='${JSON.stringify(dato)}'value="Seleccionar" name="btnSeleccionar"></th>
                                        </tr>
                                `
                        }
                    }
                            
               }
               tabla += `</table>`;
               div.innerHTML = tabla;
               this.AsignarManejadoresSeleccionSinFoto();
         };
        }
        

        static ObtenerModificarSinFoto(dato:any) 
         {
 
             let obj = dato.getAttribute("data-obj");
       
             let obj_dato = JSON.parse(obj);
       
             (<HTMLInputElement>document.getElementById("idNeumatico")).value = obj_dato.id;
             (<HTMLInputElement>document.getElementById("marca")).value = obj_dato.marca;
             (<HTMLInputElement>document.getElementById("medidas")).value = obj_dato.medidas;
             (<HTMLInputElement>document.getElementById("precio")).value = obj_dato.precio;
             
             if(<HTMLInputElement>document.getElementById("imgFoto"))
             {
                (<HTMLInputElement>document.getElementById("imgFoto")).src = obj_dato.pathFoto;
                (<HTMLInputElement> document.getElementById("foto")).src = (<HTMLInputElement>document.getElementById("imgFoto")).src;  
             }
        }     
 
        static AsignarManejadoresSeleccionSinFoto()
         {
 
             document.getElementsByName("btnSeleccionar").forEach((elemento)=>{
       
                elemento.addEventListener("click", ()=>{ this.ObtenerModificarSinFoto(elemento)});
             });
         }

          ModificarNeumatico()
        {
            let id : Number = parseInt((<HTMLInputElement> document.getElementById("idNeumatico")).value)
            let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
            let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
            let precio : Number = parseInt((<HTMLInputElement> document.getElementById("precio")).value);

            let neumatico = new Entidades.NeumaticoBd(marca,medidas,precio,id,"");

            xhttp.open('POST', './backend/modificarNeumaticoBD.php', true);

            formData.append('neumatico_json', JSON.stringify(neumatico));
  
            xhttp.send(formData);

            Manejadora.RespuestaNeumaticosBDJSON();
            Manejadora.LimpiarNeumaticosBdHTML();

        }

        static modificarNeumaticoSinFoto()
        {
            let m = new Manejadora();
            m.ModificarNeumatico()
        }

        static eliminarNeumatico ()
        {
           let m = new Manejadora();
           m.EliminarNeumatico()
        }

         EliminarNeumatico()
        {
            let id : Number = parseInt((<HTMLInputElement> document.getElementById("idNeumatico")).value)
            let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
            let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
            let precio : Number = parseInt((<HTMLInputElement> document.getElementById("precio")).value);

            let neumatico = new Entidades.NeumaticoBd(marca,medidas,precio,id,"");

            let opcion = confirm(`Seguro que quieres eliminar el neumatico ${marca} - ${medidas}?`);

            if(opcion)
            {
                xhttp.open('POST', './backend/eliminarNeumaticoBD.php', true);

                formData.append('neumatico_json', JSON.stringify(neumatico));
    
                xhttp.send(formData);
    
                Manejadora.RespuestaNeumaticosBDJSON();
                Manejadora.LimpiarNeumaticosBdHTML();
            }
            else
            {
                alert("Se cancelo la operacion");
            }
        }


        ///CON FOTO

        static verificarBd()
        {
            let m = new Manejadora();
            m.VerificarNeumaticoBd()
        }

        static agregarConFoto()
        {
            let m = new Manejadora();
            m.AgregarNeumaticoFoto()
        }

        static modificarConFoto()
        {
            let m = new Manejadora();
            m.ModificarNeumaticoBDFoto()
        }

        static eliminarConFoto()
        {
            let m = new Manejadora();
            m.BorrarNeumaticoFoto()
        }

        VerificarNeumaticoBd()
        {
            let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
            let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
            let precio : Number = parseInt((<HTMLInputElement> document.getElementById("precio")).value);

            let neumatico = new Entidades.Neumatico(marca,medidas,precio);

            xhttp.open('POST', './backend/verificarNeumaticoBD.php', true);

            formData.append('obj_neumatico', JSON.stringify(neumatico));

            xhttp.send(formData);

            xhttp.onreadystatechange = () => {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    let jsrt = JSON.parse(xhttp.responseText);
                    if(jsrt !== "")
                    {
                        alert(jsrt.marca+","+jsrt.medidas);
                    }
                    else
                    {
                        console.log(xhttp.responseText);
                        alert("No se encuentra en base de datos");
                    }
                }
            };

            
            Manejadora.LimpiarNeumaticosBdHTML();
        }


        AgregarNeumaticoFoto()
        {
               let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
               let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
               let precio : string = (<HTMLInputElement> document.getElementById("precio")).value;
               let foto : any = (<HTMLInputElement> document.getElementById("foto"));

               formData.append('marca',marca);
               formData.append('medidas',medidas);
               formData.append('precio',precio);
               formData.append('foto', foto.files[0]);
           
               xhttp.open("POST","./backend/agregarNeumaticoBD.php", true);
              
               xhttp.setRequestHeader("enctype","multipart/form-data");
               
               xhttp.send(formData);

               Manejadora.RespuestaNeumaticosBDJSON();
               Manejadora.LimpiarNeumaticosBdHTML();
        }

        ModificarNeumaticoBDFoto()
        {
            let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
            let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
            let id :Number  = parseInt((<HTMLInputElement> document.getElementById("idNeumatico")).value);
            let precio : Number = parseInt((<HTMLInputElement> document.getElementById("precio")).value);
            let nuevaFoto : any = (<HTMLInputElement> document.getElementById("foto"));


            let neumatico = new Entidades.NeumaticoBd(marca,medidas,precio,id,"");
        
            if(nuevaFoto.files[0] == null)
            {
                alert("Seleccione una foto para poder modificar");
            }
            else
            {
                xhttp.open('POST', './backend/modificarNeumaticoBDFoto.php', true);

                formData.append('foto', nuevaFoto.files[0]);
                formData.append("neumatico_json",JSON.stringify(neumatico));
    
                xhttp.setRequestHeader("enctype","multipart/form-data");
    
                xhttp.send(formData);
    
                Manejadora.RespuestaNeumaticosBDJSON();
                Manejadora.LimpiarNeumaticosBdHTML();
            }

        }

        BorrarNeumaticoFoto()
        {
            let marca : string = (<HTMLInputElement> document.getElementById("marca")).value;
            let medidas : string = (<HTMLInputElement> document.getElementById("medidas")).value;
            let id :Number  = parseInt((<HTMLInputElement> document.getElementById("idNeumatico")).value);
            let precio : Number = parseInt((<HTMLInputElement> document.getElementById("precio")).value);
            let nuevaFoto : any = (<HTMLInputElement> document.getElementById("foto"));

            let neumatico = new Entidades.NeumaticoBd(marca,medidas,precio,id,"");
           

            xhttp.open('POST', './backend/eliminarNeumaticoBDFoto.php', true);

            formData.append('foto', nuevaFoto.files[0]);
            formData.append("neumatico_json",JSON.stringify(neumatico));

            xhttp.setRequestHeader("enctype","multipart/form-data");

            xhttp.send(formData);

            Manejadora.RespuestaNeumaticosBDJSON();
            Manejadora.LimpiarNeumaticosBdHTML();
        }

    }

}