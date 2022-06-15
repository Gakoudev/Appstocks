/**
 * 
 * @param {HTMLSelectElement} select 
 */
function bindSelect(select){
    new TomSelect(select,{
        hideSelected:true,
        closeAfterSelect:true,
        plugins:{
            remove_button: {title:'Supprimer cet élément'}
        }
    })
}

Array.from(document.querySelectorAll('select[multiple]')).map(bindSelect)