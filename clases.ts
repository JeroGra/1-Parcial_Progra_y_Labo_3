namespace Entidades
{
    export class Neumatico
    {
        marca : string;
        medidas : string;
        precio : Number;

        constructor(marca:string,medidas:string,precio:Number)
        {
            this.marca = marca;
            this.medidas = medidas;
            this.precio = precio;
        }

        toString():string
        {
            let str = "marca:"+this.marca+","+"medidas:"+this.medidas+","+"precio:"+this.precio;
            return str;
        }

        ToJSON()
        {
            let js = JSON.stringify(this.toString());
            return js;
        }

    }

    export class NeumaticoBd extends Neumatico
    {
        id : Number;
        pathFoto : string;

        constructor(marca?:string,medidas?:string,precio?:Number,id?:Number,pathFoto?:string)
        {
            super(marca ? marca:"",medidas ? medidas:"",precio ? precio:0);
            this.id = id ? id : 0;
            this.pathFoto = pathFoto ? pathFoto : "";
        }

        ToJSON(): string 
        {
            let js = super.toString()+","+"id:"+this.id+","+"pathFoto:"+this.pathFoto;
            js = JSON.stringify(js);
            return js;
        }
    }
}