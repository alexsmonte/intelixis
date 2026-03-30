 function aplicarMascaras(configs) {
    if (typeof IMask === "undefined") {
      console.error("IMask não foi carregado.");
      return;
    }
  
    configs.forEach(cfg => {
      const el = document.getElementById(cfg.id);
      if (!el) return;
  
      const options = { ...cfg };
      delete options.id;
  
      IMask(el, options);
    });
  }
  
  document.addEventListener('DOMContentLoaded', function() {
    aplicarMascaras([
      { id: 'documento', mask: '000.000.000-00' },
      { id: 'cnpj',      mask: '00.000.000/0000-00' },
      { id: 'telefone',  mask: '(00) 0000-0000' },
      { id: 'celular',   mask: '(00) 00000-0000' },
      { id: 'cep',       mask: '00000-000' },
  
      // UF maiúsculo
      {
        id: 'uf',
        mask: /^[A-Za-z]{0,2}$/,
        prepare: str => str.toUpperCase(),
        onAccept: function(value, maskRef) {
          maskRef.el.value = value.toUpperCase();
        }
      },
  
      // VALOR: monetário fixo 2 casas decimais
      {
        id: 'valor',
        mask: Number,
        thousandsSeparator: '.',
        radix: ',',
        scale: 2,
        normalizeZeros: true,
        padFractionalZeros: true,
        lazy: false,
        prefix: 'R$ '
      },

      
    
      {
        id: 'taxas',
        mask: Number,
        scale: 2,
        signed: false,
        thousandsSeparator: '.',
        padFractionalZeros: true,
        normalizeZeros: true,
        radix: ',', 
        mapToRadix: ['.'], 
        lazy: false,
      },
      {
        id: 'taxa_antecipacao',
        mask: Number,
        scale: 2,
        signed: false,
        thousandsSeparator: '.',
        padFractionalZeros: true,
        normalizeZeros: true,
        radix: ',',
        mapToRadix: ['.'],
        lazy: false,
      },
      {
        id: 'dias',
        mask: Number,
        signed: false,
        scale: 0, // sem casas decimais
        radix: ',',
        thousandsSeparator: '',
      }
      
      
    ]);
  });
  