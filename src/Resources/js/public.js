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
            purgeMarket(cfoMarketButton.dataset.cfoMarket);
        };
    }
    
    async function purgeMarket(marketID) {
        try {
            const response = await fetch('/wp-json/cfo/purge_market?' + new URLSearchParams({market_id: marketID}).toString() , {
                headers: {
                    'X-WP-Nonce': myUserData.nonce
                },
                credentials: 'same-origin',
            });
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }
            const text = await response.text();
            if (text.length !== 0) {
                console.log(text.length);
            }
        } catch (error) {
            console.error(error);
        }
    }
});