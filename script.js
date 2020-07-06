let invoiceRow = document.getElementById("invoice_line_item");
let rowClone = invoiceRow.cloneNode(true);

let categoryInput = document.getElementById("category_input");
let categoryInputClone = categoryInput.cloneNode(true);

let itemInput = document.getElementById("item_input");
let itemInputClone = itemInput.cloneNode(true);

let itemDropdown = document.getElementById("item_name");
let itemDropdownClone = itemDropdown.cloneNode(true);

let subtotal = 0;

function submitForm() {
	let form = document.getElementById("nav-form");
	form.action = event.target.id;
	form.submit();
}

function addRow() {
	event.preventDefault();
	let tableBody = document.getElementById("invoice_items");
	let newInstance = rowClone.cloneNode(true);
	tableBody.appendChild(newInstance);
}

function deleteRow() {
	event.preventDefault();
	event.stopPropagation();
	let tableRows = document.querySelectorAll("#invoice_line_item");
	for (let i = 0; i < tableRows.length; i++) {
		if (tableRows[i].querySelector(".itemRow").checked) {
			if (tableRows[i].parentNode) {
				subtotal -= +tableRows[i].childNodes[13].childNodes[0].value;
				tableRows[i].parentNode.removeChild(tableRows[i]);
			}
		}
	}
	document.getElementById("subtotal_input").value = parseFloat(
		subtotal
	).toFixed(2);

	let taxRate = +document.getElementById("tax_rate_input").value / 100;
	let estimatedTax = taxRate * subtotal;

	document.getElementById("est_tax_input").value = parseFloat(
		estimatedTax
	).toFixed(2);

	let amountDue = subtotal + estimatedTax;

	document.getElementById("amt_due_input").value = parseFloat(
		amountDue
	).toFixed(2);
}

function selectCategory() {
	console.log("Should change!");
	let parent = event.target.parentNode.parentNode;
	if (event.target.value === "new") {
		if (!parent.childNodes[3].childNodes[3]) {
			let newInstance = categoryInputClone.cloneNode(true);
			newInstance.style.display = "block";
			let selectElement = parent.childNodes[3].childNodes[1];
			selectElement.parentNode.insertBefore(newInstance, selectElement);
			selectElement.parentNode.removeChild(selectElement);

			if (!parent.childNodes[5].childNodes[3]) {
				newInstance = itemInputClone.cloneNode(true);
				newInstance.style.display = "block";
				selectElement = parent.childNodes[5].childNodes[1];
				selectElement.parentNode.insertBefore(newInstance, selectElement);
				selectElement.parentNode.removeChild(selectElement);

				for (let i = 7; i < parent.childNodes.length - 2; i += 2) {
					parent.childNodes[i].childNodes[0].disabled = false;
					parent.childNodes[i].childNodes[0].value = "";
				}
			} else {
				parent.childNodes[5].childNodes[3].style.display = "block";
				parent.childNodes[5].childNodes[1].style.display = "none";

				selectElement = parent.childNodes[5].childNodes[1];
				selectElement.parentNode.removeChild(selectElement);

				for (let i = 7; i < parent.childNodes.length - 2; i += 2) {
					parent.childNodes[i].childNodes[0].disabled = false;
					parent.childNodes[i].childNodes[0].value = "";
				}
			}
		} else {
			parent.childNodes[3].childNodes[3].style.display = "block";
			parent.childNodes[3].childNodes[1].style.display = "none";

			let selectElement = parent.childNodes[3].childNodes[1];
			selectElement.parentNode.removeChild(selectElement);
			selectElement = parent.childNodes[3].childNodes[0];
			selectElement.parentNode.removeChild(selectElement);

			parent.childNodes[5].childNodes[3].style.display = "block";
			parent.childNodes[5].childNodes[1].style.display = "none";

			selectElement = parent.childNodes[5].childNodes[1];
			selectElement.parentNode.removeChild(selectElement);
			selectElement = parent.childNodes[5].childNodes[0];
			selectElement.parentNode.removeChild(selectElement);

			for (let i = 7; i < parent.childNodes.length - 2; i += 2) {
				parent.childNodes[i].childNodes[0].disabled = false;
				parent.childNodes[i].childNodes[0].value = "";
			}
		}
	} else {
		if (parent.childNodes[3].childNodes[3]) {
			let selectElement = parent.childNodes[3].childNodes[3];
			selectElement.parentNode.removeChild(selectElement);
			selectElement = parent.childNodes[3].childNodes[3];
			selectElement.parentNode.removeChild(selectElement);
		}
		if (parent.childNodes[5].childNodes[1].id === "item_input") {
			let newInstance = itemDropdownClone.cloneNode(true);
			let selectElement = parent.childNodes[5].childNodes[1];
			selectElement.parentNode.insertBefore(newInstance, selectElement);
			selectElement.parentNode.removeChild(selectElement);
		}

		let xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				const arrResponse = this.responseText.split("");
				for (i = 0; i < arrResponse.length; i++) {
					if (
						arrResponse[i] === "<" &&
						arrResponse[i + 1] === "!" &&
						arrResponse[i + 2] === "D"
					) {
						arrResponse.splice(i - 1, arrResponse.length - (i + 1));
						break;
					}
				}
				const newResponse = arrResponse.join("");

				parent.childNodes[5].childNodes[1].innerHTML = newResponse;
			}
		};
		xmlhttp.open("GET", "/index.php?category=" + event.target.value, true);
		xmlhttp.send();

		for (let i = 5; i < parent.childNodes.length - 2; i += 2) {
			if (parent.childNodes[i].childNodes[1]) {
				parent.childNodes[i].childNodes[1].disabled = false;
				parent.childNodes[i].childNodes[1].value = "";
			} else {
				parent.childNodes[i].childNodes[0].disabled = false;
				parent.childNodes[i].childNodes[0].value = "";
			}
		}
	}
}

function selectItem() {
	let parent = event.target.parentNode.parentNode;
	if (event.target.value === "new") {
		if (!parent.childNodes[5].childNodes[3]) {
			let newInstance = itemInputClone.cloneNode(true);
			newInstance.style.display = "block";
			let selectElement = parent.childNodes[5].childNodes[1];
			selectElement.parentNode.insertBefore(newInstance, selectElement);
			selectElement.parentNode.removeChild(selectElement);

			for (let i = 7; i < parent.childNodes.length - 2; i += 2) {
				parent.childNodes[i].childNodes[0].disabled = false;
				parent.childNodes[i].childNodes[0].value = "";
			}
		} else {
			parent.childNodes[5].childNodes[3].style.display = "block";
			parent.childNodes[5].childNodes[1].style.display = "none";

			let selectElement = parent.childNodes[5].childNodes[1];
			selectElement.parentNode.removeChild(selectElement);
			selectElement = parent.childNodes[5].childNodes[0];
			selectElement.parentNode.removeChild(selectElement);
		}
	} else {
		if (parent.childNodes[5].childNodes[3]) {
			let selectElement = parent.childNodes[5].childNodes[3];
			selectElement.parentNode.removeChild(selectElement);
			selectElement = parent.childNodes[5].childNodes[3];
			selectElement.parentNode.removeChild(selectElement);
		}

		let categoryName = parent.childNodes[3].childNodes[1].value;
		console.log(categoryName);

		let xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function () {
			if (this.readyState == 4 && this.status == 200) {
				let firstResponse = this.responseText.split("Add An Item");
				firstResponse.splice(0, 1);
				firstArrResponse = firstResponse.join("").split("");

				for (i = 0; i < firstArrResponse.length; i++) {
					if (
						firstArrResponse[i] === "o" &&
						firstArrResponse[i + 1] === "n" &&
						firstArrResponse[i + 2] === ">"
					) {
						firstArrResponse.splice(0, i + 3);
						break;
					}
				}

				const arrResponse = firstArrResponse.join("").split("");

				for (i = 0; i < arrResponse.length; i++) {
					if (
						arrResponse[i] === "<" &&
						arrResponse[i + 1] === "!" &&
						arrResponse[i + 2] === "D"
					) {
						arrResponse.splice(i, arrResponse.length - i);
						break;
					}
				}
				const newResponse = arrResponse.join("").split(",");
				newResponse[0] = newResponse[0].replace(/(\r\n|\n|\r)/gm, "");
				newResponse[1] = newResponse[1].replace(/(\r\n|\n|\r)/gm, "");

				parent.childNodes[7].childNodes[0].value = newResponse[0];
				parent.childNodes[11].childNodes[0].value = parseFloat(
					newResponse[1]
				).toFixed(2);
			}
		};
		xmlhttp.open(
			"GET",
			"/index.php?item=" + event.target.value + "&category=" + categoryName,
			true
		);
		xmlhttp.send();
	}
}

function calculateTotal() {
	event.target.value = parseFloat(event.target.value).toFixed(2);

	let parent = event.target.parentNode.parentNode;
	let total =
		parent.childNodes[9].childNodes[0].value *
		parent.childNodes[11].childNodes[0].value;

	subtotal -= +parent.childNodes[13].childNodes[0].value;
	parent.childNodes[13].childNodes[0].value = parseFloat(total).toFixed(2);
	subtotal += +parent.childNodes[13].childNodes[0].value;

	document.getElementById("subtotal_input").value = parseFloat(
		subtotal
	).toFixed(2);

	let taxRate = +document.getElementById("tax_rate_input").value / 100;
	let estimatedTax = taxRate * subtotal;

	document.getElementById("est_tax_input").value = parseFloat(
		estimatedTax
	).toFixed(2);

	let amountDue = subtotal + estimatedTax;

	document.getElementById("amt_due_input").value = parseFloat(
		amountDue
	).toFixed(2);
}

function updateTotal() {
	event.target.value = parseFloat(event.target.value).toFixed(1);

	let taxRate = +document.getElementById("tax_rate_input").value / 100;
	let estimatedTax = taxRate * subtotal;

	document.getElementById("est_tax_input").value = parseFloat(
		estimatedTax
	).toFixed(2);

	let amountDue = subtotal + estimatedTax;

	document.getElementById("amt_due_input").value = parseFloat(
		amountDue
	).toFixed(2);
}

function addDecimals() {
	this.value = parseFloat(this.value).toFixed(2);
}
