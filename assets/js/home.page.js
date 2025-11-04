import { onClickViewTour } from "./utils.module.js";

(()=> {
    document.addEventListener('readystatechange', ()=> {
        const viewTourBtn = document.querySelectorAll('.view-tour-page');

        viewTourBtn.forEach((btn)=> {
            btn.addEventListener('click', (event)=> {
                console.log('Tour Clciked')
                event.preventDefault();
                 const id = event.currentTarget.getAttribute("data-tour-id");
                onClickViewTour(id);    
            })
        })
    })
})()