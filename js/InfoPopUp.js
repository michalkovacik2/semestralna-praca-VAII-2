/**
 * InfoPopUp is used to display success or alert messages to user as a pop up window
 */
class InfoPopUp
{

    /**
     * Constructor
     * @param text - text to show
     */
    constructor(text)
    {
        this._modalTEXT = document.getElementById('modalTEXT');
        this._modalIcon = document.getElementById('modalIcon');
        this._modalButton = document.getElementById('modalButton');

        this._modalTEXT.style.fontWeight = 'bold';
        this._modalTEXT.innerHTML = text;
    }

    /**
     * Sets pop up window theme to success
     */
    setSuccess()
    {
        this._modalIcon.innerHTML = `<i class="fas fa-check-circle logoModal"></i>`;
        this._modalIcon.childNodes[0].style.color = 'green';
        this._modalButton.classList.remove("btn-danger");
        this._modalButton.classList.add("btn-success");
    }

    /**
     * Sets pop up theme to alert
     */
    setAlert()
    {
        this._modalIcon.innerHTML = `<i class="fas fa-times-circle logoModal"></i>`;
        this._modalIcon.childNodes[0].style.color = 'red';
        this._modalButton.classList.remove("btn-success");
        this._modalButton.classList.add("btn-danger");
    }

    /**
     * shows the pop up window
     */
    show()
    {
        $('#modal').modal('show');
    }
}