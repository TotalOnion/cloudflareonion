/*
*   Function to update ingredient amounts
*   Please make sure that you feed it the following fields:
*   convertToOriginal: whether or not you're converting from or to the original unit
*   multiplier: how many servings
*   ingredientElements: the elements that contain the amount and units
*   originalAmtDataLabel: data attribute containing the original amt, camel-case (i.e. if attribute is data-original-amt then pass in `originalAmt`)
*   originalUnitDataLabel: data attribute containing the original unit label, camel-case (i.e. if attribute is data-original-amt then pass in `originalAmt`)
*   convertToAmtDataLabel: data attribute containing the conversion amt, camel-case (i.e. if attribute is data-original-amt then pass in `originalAmt`)
*   convertToUnitDataLabel: data attribute containing the conversion unit label, camel-case (i.e. if attribute is data-original-amt then pass in `originalAmt`)
*/
window.updateIngredientAmounts = function (
    convertToOriginal = true,
    multiplier = 1,
    ingredientElements,
    originalAmtDataLabel = 'originalAmt', // make sure this is camelCase
    originalUnitDataLabel = 'originalUnit', // make sure this is camelCase
    convertToAmtDataLabel = 'convertToAmt', // make sure this is camelCase
    convertToUnitDataLabel = 'convertToUnit', // make sure this is camelCase
) {
    if ( ingredientElements && ingredientElements.length ) {
        ingredientElements.forEach((ingredient) => {
            const value = convertToOriginal
                ? ingredient.dataset[originalAmtDataLabel]
                : ingredient.dataset[convertToAmtDataLabel];
            const unit = convertToOriginal
                ? ingredient.dataset[originalUnitDataLabel]
                : ingredient.dataset[convertToUnitDataLabel];
            ingredient.innerHTML = `${
                value
                    ? Number((value * multiplier).toFixed(2))
                            .toString()
                            .replace(/\B(?=(\d{3})+(?!\d))/g, ',') // add commas to number
                    : ''
            }${unit ? ` ${unit}` : ''}`;
        });
    }
};