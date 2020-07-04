let invoiceRow = document.getElementById('invoice_line_item');
let clone = invoiceRow.cloneNode(true)
let subtotal = 0

function submitForm(){
    let form = document.getElementById('nav-form')
    form.action = event.target.id
    form.submit()
}

function addRow(){
    event.preventDefault()
    let tableBody = document.getElementById('invoice_items')
    let newInstance = clone.cloneNode(true)
    tableBody.appendChild(newInstance)
}

function deleteRow(){
    event.preventDefault()
    event.stopPropagation()
    let tableRows = document.querySelectorAll('#invoice_line_item')
    for(let i=0; i < tableRows.length; i++){
        if(tableRows[i].querySelector('.itemRow').checked){
            if(tableRows[i].parentNode){
                subtotal -= +tableRows[i].childNodes[13].childNodes[0].value
                tableRows[i].parentNode.removeChild(tableRows[i])
            }
        }
    }
    document.getElementById('subtotal_input').value = subtotal

    let taxRate = +document.getElementById('tax_rate_input').value / 100
    let estimatedTax = taxRate * subtotal

    document.getElementById('est_tax_input').value = estimatedTax

    let amountDue = subtotal + estimatedTax

    document.getElementById('amt_due_input').value = amountDue
}

function selectCategory(){
    let parent = event.target.parentNode.parentNode
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if(this.readyState == 4 && this.status == 200){
            const arrResponse = this.responseText.split('');
            for(i=0; i<arrResponse.length; i++){
                if(arrResponse[i] === '<' && arrResponse[i+1] === '!' && arrResponse[i+2] === 'D' ){
                    arrResponse.splice(i-1, arrResponse.length - (i+1))
                    break
                }
            }
            const newResponse = arrResponse.join('')

            parent.childNodes[5].childNodes[1].innerHTML = newResponse
        }
    };
    xmlhttp.open("GET", "/index.php?category=" + event.target.value, true);
    xmlhttp.send();

    for(let i=5; i < parent.childNodes.length - 2; i+=2){
        if(parent.childNodes[i].childNodes[1]){
            parent.childNodes[i].childNodes[1].disabled = false
        } else {
            parent.childNodes[i].childNodes[0].disabled = false
        }
    }
}

function selectItem(){
    let parent = event.target.parentNode.parentNode

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if(this.readyState == 4 && this.status == 200){
            const arrResponse = this.responseText.split('');
            for(i=0; i<arrResponse.length; i++){
                if(arrResponse[i] === '<' && arrResponse[i+1] === '!' && arrResponse[i+2] === 'D' ){
                    arrResponse.splice(i, arrResponse.length - i)
                    break
                }
            }
            const newResponse = arrResponse.join('').split(',')
            newResponse[1] = newResponse[1].replace(/(\r\n|\n|\r)/gm, "");

            parent.childNodes[7].childNodes[0].value = newResponse[0]
            parent.childNodes[11].childNodes[0].value = +newResponse[1]
        }
    };
    xmlhttp.open("GET", "/index.php?item=" + event.target.value, true);
    xmlhttp.send();
}

function calculateTotal() {
    let parent = event.target.parentNode.parentNode
    let total = parent.childNodes[9].childNodes[0].value * parent.childNodes[11].childNodes[0].value

    subtotal -= +parent.childNodes[13].childNodes[0].value
    parent.childNodes[13].childNodes[0].value = total
    subtotal += +parent.childNodes[13].childNodes[0].value

    document.getElementById('subtotal_input').value = subtotal

    let taxRate = +document.getElementById('tax_rate_input').value / 100
    let estimatedTax = taxRate * subtotal

    document.getElementById('est_tax_input').value = estimatedTax

    let amountDue = subtotal + estimatedTax

    document.getElementById('amt_due_input').value = amountDue
}

function updateTotal(){
    let taxRate = +document.getElementById('tax_rate_input').value / 100
    let estimatedTax = taxRate * subtotal

    document.getElementById('est_tax_input').value = estimatedTax

    let amountDue = subtotal + estimatedTax

    document.getElementById('amt_due_input').value = amountDue
}