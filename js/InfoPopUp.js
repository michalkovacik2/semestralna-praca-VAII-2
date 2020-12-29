class InfoPopUp
{
    constructor()
    {
        this._modalTEXT = document.getElementById('modalTEXT');
        this._modalIcon = document.getElementById('modalIcon');
        this._modalButton = document.getElementById('modalButton');
    }

    setSuccess(text)
    {
        this._modalTEXT.style.fontWeight = 'bold';
        this._modalTEXT.innerText = text;
        this._modalIcon.innerHTML = `<i class="fas fa-check-circle logoModal"></i>`;
        this._modalIcon.childNodes[0].style.color = 'green';
        this._modalButton.classList.remove("btn-danger");
        this._modalButton.classList.add("btn-success");
    }

    setAlert(text)
    {
        this._modalTEXT.style.fontWeight = 'bold';
        this._modalTEXT.innerHTML = text;
        this._modalIcon.innerHTML = `<i class="fas fa-times-circle logoModal"></i>`;
        this._modalIcon.childNodes[0].style.color = 'red';
        this._modalButton.classList.remove("btn-success");
        this._modalButton.classList.add("btn-danger");
    }

    show()
    {
        $('#modal').modal('show');
    }
}