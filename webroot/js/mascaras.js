function aplicarMascaras(configs) {
    if (typeof IMask === "undefined") {
      console.error("IMask não foi carregado.");
      return;
    }
  
    configs.forEach(cfg => {
      const el = document.getElementById(cfg.id);
      if (el) {
        IMask(el, { mask: cfg.mask });
      }
    });
  }
  
  document.addEventListener('DOMContentLoaded', function() {
    aplicarMascaras([
      { id: 'documento', mask: '000.000.000-00' },
      { id: 'cnpj',  mask: '(00) 0000-0000' },
      { id: 'telefone',  mask: '(00) 0000-0000' },
      { id: 'celular',   mask: '(00) 00000-0000' },
      { id: 'cep',       mask: '00000-000' },
     
    ]);
  });
  