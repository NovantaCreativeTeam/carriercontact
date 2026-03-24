const $ = window.$;

$(() => {
  const quotesGrid = new window.prestashop.component.Grid('carrierContacts')
  quotesGrid.addExtension(new window.prestashop.component.GridExtensions.FiltersResetExtension())
  quotesGrid.addExtension(new window.prestashop.component.GridExtensions.SortingExtension())
  quotesGrid.addExtension(new window.prestashop.component.GridExtensions.SubmitRowActionExtension())
});
