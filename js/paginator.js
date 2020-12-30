/**
 * Paginator is used to generate page numbers on site
 */
class Paginator
{
    /**
     * Constructor
     * @param numberOfData - total amount of all data
     * @param itemsOnOnePage - number of items we want on one page
     */
    constructor(numberOfData, itemsOnOnePage)
    {
        let numSites = Math.floor(numberOfData/itemsOnOnePage);
        this._numberOfPages = numberOfData % itemsOnOnePage !== 0 ? numSites + 1 : numSites;
    }

    /**
     * Used to display page numbers on site
     * @param link where should the page number point to
     */
    displayPages(link = null)
    {
        let html = `<ul class="pagination justify-content-center mt-3">`;
        for (let i = 0; i < this._numberOfPages; i++)
        {
            html += `<li class="page-item">
                        <a id="paginator`+ (i+1) +`" class="page-link" href="`+ (link == null ? "#" : link ) +`">` + (i+1) + `</a></li>`;
        }
        html += `</ul>`;
        document.getElementById("paginator").innerHTML = html;
    }

    /**
     * Get total number of pages
     * @returns {number}
     */
    getNumberOfPages()
    {
        return this._numberOfPages;
    }
}
