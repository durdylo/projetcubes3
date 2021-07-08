function insertIngredients(data){
    let ingredients = data;
    console.log(data)
}

function getIngredients() {

    fetch("http://localhost/web/projetCubes3/Models/ingredient/selectIngredient.php" , {
        method: "GET",
        Options: {
            'Access-Control-Allow-Origin': '*'
        }
    })
        .then(response => response.json())
        .then(json => insertIngredients(json));
}


console.log(getIngredients());