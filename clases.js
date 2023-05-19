"use strict";
var Entidades;
(function (Entidades) {
    class Neumatico {
        constructor(marca, medidas, precio) {
            this.marca = marca;
            this.medidas = medidas;
            this.precio = precio;
        }
        toString() {
            let str = "marca:" + this.marca + "," + "medidas:" + this.medidas + "," + "precio:" + this.precio;
            return str;
        }
        ToJSON() {
            let js = JSON.stringify(this.toString());
            return js;
        }
    }
    Entidades.Neumatico = Neumatico;
    class NeumaticoBd extends Neumatico {
        constructor(marca, medidas, precio, id, pathFoto) {
            super(marca ? marca : "", medidas ? medidas : "", precio ? precio : 0);
            this.id = id ? id : 0;
            this.pathFoto = pathFoto ? pathFoto : "";
        }
        ToJSON() {
            let js = super.toString() + "," + "id:" + this.id + "," + "pathFoto:" + this.pathFoto;
            js = JSON.stringify(js);
            return js;
        }
    }
    Entidades.NeumaticoBd = NeumaticoBd;
})(Entidades || (Entidades = {}));
