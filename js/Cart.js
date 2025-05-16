class Cart{
    constructor(){
        this.products = [];
        this.total = 0;
    }
    addProduct(product){
        // Comprobar si el producto ya está en el carrito
        const isProduct = this.products.some(p => p.id_producto == product.id_producto);
        if(isProduct){
            return false;
        } else {
            this.products.push(product);
            this.products = this.products.map(p => {
                if(p.id_producto == product.id_producto){
                    p.cantidad = 1;
                }
                return p;
            }
            );
            this.total += product.precio;
            return true;
        }
    }
    aumentarCantidad(id_producto){
        const listProducts = [...this.products];
        const newList=  listProducts.map(p => {
            if(p.id_producto == id_producto){
                p.cantidad++;
            }
            return p;
        });
        this.products = newList;
    }
    disminuirCantidad(id_producto){
        const listProducts = [...this.products];
        const newList=  listProducts.map(p => {
            if(p.id_producto == id_producto){
                p.cantidad--;
            }
            return p;
        });

        this.products = newList;
    }
    removeProduct(product){
        this.products = this.products.filter(p => p.id_producto != product.id_producto);
        this.total -= product.precio;
    }
    calculateTotal(){
        this.total = this.products.reduce((total, product) => total + product.precio * product.cantidad, 0);
        return this.total;
    }
    getProducts(){
        return this.products;
    }
    cleanCart(){
        this.products = [];
        this.total = 0;
    }
}
export {Cart};