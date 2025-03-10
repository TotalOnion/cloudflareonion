// Initialize SnapSelect on all select elements with the class 'snapSelect'
document.addEventListener('DOMContentLoaded', () => {

    SnapSelect('.snapSelect', {
        liveSearch: true,
        placeholder: 'Select Post Types...',
        clearAllButton: true,
        closeOnSelect: false,
        allowEmpty: true
    });

    let cfoMarkets = document.getElementById('cfo-purge-container').children;
    for (const cfoMarketButton of cfoMarkets) {
        cfoMarketButton.onclick = function () {
            if (confirm("Are you sure you want to clear the cache for " + cfoMarketButton.children[0].innerHTML)) {
                purgeMarket(cfoMarketButton.dataset.cfoMarket);
            }
        };
    }
    
    async function purgeMarket(marketID) {
        try {
            const resultContainer = document.getElementById('cfo-purge-result');
            resultContainer.innerHTML = '';
            resultContainer.classList.add('cfo-loading');
            const response = await fetch('/wp-json/cfo/purge_market?' + new URLSearchParams({market_id: marketID}).toString() , {
                headers: {
                    'X-WP-Nonce': myUserData.nonce
                },
                credentials: 'same-origin',
            });
            if (!response.ok) {
                resultContainer.classList.remove('cfo-loading');
                throw new Error(`Response status: ${response.status}`);
            }
            const json = await response.json();
            let textToDisplay = 'Nothing to display.';
            try {
                let jsonResponse = JSON.parse(json);
                if (jsonResponse.success == true) {
                    textToDisplay = 'Successfully purged.';
                } else {
                    textToDisplay = JSON.stringify(jsonResponse.errors[0].message);
                }
            } catch (error) {
                textToDisplay = json;
            }
            resultContainer.classList.remove('cfo-loading');
            resultContainer.innerHTML = textToDisplay;
        } catch (error) {
            console.error(error);
        }
    }
});