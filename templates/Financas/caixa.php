<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Caixa $caixa
 * @var \App\Model\Entity\Financa[] $lancamentos
 * @var \App\Model\Entity\FormaPagamento[] $formasPagamento
 */

$this->assign('title', 'Caixa');

?>
<meta name="csrfToken" content="<?= $this->request->getAttribute('csrfToken') ?>">
<div class="container-fluid" id="app-caixa" v-cloak>

    
    <!-- Busca Form -->
    <div class="card mb-4 shadow-sm border-0" style="border-radius: 8px;">
        <div class="card-body p-4">
            <!-- TITULO E BUSCA -->
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0">PDV - Caixa</h4>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-md-6 position-relative">
                    <div class="input-group">
                        <input type="text" 
                            id="searchClient"
                            class="form-control form-control-lg placeholdert" 
                            placeholder="pesquisar paciente ou descrição..." 
                            v-model="query"
                            @input="debouncedSearch"
                            @keydown.enter="buscar">
                        <button class="btn bg-primary-subtle text-primary border-primary" @click="buscar" title="Pesquisar">
                            <i class="ti ti-search fs-5"></i>
                        </button>
                    </div>
                
                    <!-- Resultados da busca dropdown -->
                    <ul class="list-group position-absolute w-100 shadow-sm z-3" v-if="resultados.length > 0 && query.length >= 2" style="max-height: 300px; overflow-y: auto; top: 100%; border-radius: 8px; margin-top: 5px;">
                        <li class="list-group-item list-group-item-action cursor-pointer" 
                            v-for="res in resultados" 
                            :key="res.id"
                            @click="selecionarLancamento(res)">
                            <div class="d-flex justify-content-between">
                                <strong>{{ res.descricao }}</strong>
                                <span ><strong>Vencimento: </strong>{{ formatarDataBR(res.data_vencimento) }}</span>
                
                                <span class="text-success fw-bold">{{ converterParaBrasileiro(res.valor) }}</span>
                                
                            </div>
                            <small class="text-muted" v-if="res.cliente">{{ res.cliente.nome }}</small>
                           
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Resumo -->
    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 8px;">
        <div class="card-body d-flex align-items-center justify-content-between p-4 flex-wrap">
            <div class="text-start me-4">
                <p class="text-dark fw-bold mb-1">Itens</p>
                <h4 class="text-muted fw-light mb-0">{{ itensSelecionados.length.toString().padStart(4, '0') }}</h4>
            </div>
            
            <div class="text-start me-4">
                <p class="text-dark fw-bold mb-1">Total a pagar</p>
                <h4 class="text-muted fw-light mb-0">R$ {{ converterParaBrasileiro(totalAPagar) }}</h4>
            </div>
            
            <div class="text-start me-4">
                <p class="text-dark fw-bold mb-1">Total a receber</p>
                <h4 class="text-muted fw-light mb-0">R$ {{ converterParaBrasileiro(totalRecebido) }}</h4>
            </div>
            
            <div class="text-start me-4">
                <p class="text-dark fw-bold mb-1">Troco</p>
                <h4 class="text-muted fw-light mb-0" :class="{'text-danger': troco < 0, 'text-success': troco > 0}">
                    R$ {{ converterParaBrasileiro(Math.abs(troco)) }}
                </h4>
            </div>
            
            <div>
                 <input type="hidden" name="_csrfToken" :value="csrfToken">
                <button class="btn bg-success-subtle text-success px-4 py-2" style="font-weight: 600;" 
                    @click="finalizarEReceber" 
                    :disabled="itensSelecionados.length === 0 || troco < 0 || processando">
                    <span v-if="processando" class="spinner-border spinner-border-sm me-2"></span>
                    Finalizar e Receber
                </button>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Lista de Itens -->
        <div class="col-md-7 col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 8px;">
                <div class="card-body">
                    <h5 class="card-title text-dark fw-bold mb-4">Itens</h5>
                    
                    <div class="table-responsive">
                        <table class="table border-light table-hover hover-c">
                            <thead class="text-muted border-bottom">
                                <tr>
                                    <th class="fw-normal">Descrição</th>
                                    <th class="fw-normal text-center">Vencimento</th>
                                    <th class="fw-normal text-end">Valor</th>
                                    <th class="fw-normal text-end">Desconto</th>
                                    <th class="fw-normal text-end">Total a pagar</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="itensSelecionados.length === 0">
                                    <td colspan="6" class="text-center py-4 border-light text-muted" style="border-radius: 5px;">
                                        Nenhum item encontrado
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in itensSelecionados" :key="item.id" class="align-middle border-bottom">
                                    <td>
                                        <div class="fw-medium text-dark">{{ item.descricao }}</div>
                                        <small class="text-muted" v-if="item.cliente_nome">{{ item.cliente_nome }}</small>
                                    </td>
                                    <td class="text-center">{{ formatarDataBR(item.data_vencimento) }}</td>
                                    <td class="text-end">R$ {{ converterParaBrasileiro(item.valor) }}</td>
                                    <td class="text-end" style="width: 120px;">
                                        <input type="text" class="form-control form-control-sm text-end" v-model="item.desconto" 
                                            @input="calcTotalItem(item)" 
                                            @blur="formatarDesconto(item)"
                                            placeholder="0,00">
                                    </td>
                                    <td class="text-end fw-bold">R$ {{ converterParaBrasileiro(item.total_pagar) }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm text-danger" @click="removerItem(index)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formas de Pagamento -->
        <div class="col-md-5 col-lg-4 mt-4 mt-md-0">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 8px;">
                <div class="card-body">
                    <h5 class="card-title text-dark fw-bold mb-4">Meios de pagamento</h5>
                    
                    <!-- Novo Pagamento Form -->
                    <div class="row g-2 align-items-end mb-4 border border-light p-3 rounded">
                        <div class="col-6">
                            <label class="form-label text-muted small mb-1">Forma de pgto.</label>
                            <select class="form-select form-select-sm border-0" v-model="novoPgto.forma_id">
                                <option value="">Selecione...</option>
                                <option v-for="fp in listaFormasPagamento" :value="fp.id" :key="fp.id">{{ fp.nome }}</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="form-label text-muted small mb-1">Parc.</label>
                            <input type="number" class="form-control form-control-sm border-0" v-model.number="novoPgto.parcelas" min="1">
                        </div>
                        <div class="col-3">
                            <label class="form-label text-muted small mb-1">Valor</label>
                            <input type="text" class="form-control form-control-sm border-0 text-end" v-model="novoPgto.valor" placeholder="0,00" @input="formatNovoPgtoValor" @blur="onBlurNovoPgtoValor">
                        </div>
                        <div class="col-1 text-end">
                            <button class="btn bg-success-subtle text-success btn-sm w-200 h-200" @click="addPagamento" :disabled="!novoPgto.forma_id || valorStrToFloat(novoPgto.valor) <= 0">
                                <i class="fas fa-plus"></i></button>
                        </div>
                        <div class="col-12 mt-2 text-end" v-if="troco < 0 && itensSelecionados.length > 0">
                             <a href="#" @click.prevent="preencherRestante" class="small text-decoration-none">Preencher valor pendente</a>
                        </div>
                    </div>

                    <!-- Lista de Pagamentos Adicionados -->
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr v-for="(pgto, idx) in pagamentosAdicionados" :key="idx" class="border-bottom">
                                    <td class="text-muted">{{ getFormaNome(pgto.forma_id) }}</td>
                                    <td class="text-center text-muted">{{ pgto.parcelas }}x</td>
                                    <td class="text-end fw-medium">R$ {{ converterParaBrasileiro(pgto.valor) }}</td>
                                    <td class="text-end" style="width: 30px;">
                                        <button class="btn btn-link btn-sm text-danger p-0" @click="removerPagamento(idx)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
const URLS = {
    search: '<?= $this->Url->build("/financas/searchOpen") ?>',
    checkout: '<?= $this->Url->build("/financas/pdvCheckout") ?>'
};

const app = Vue.createApp({
    data() {
        return {
            query: '',
            resultados: [],
            itensSelecionados: [],
            pagamentosAdicionados: [],
            listaFormasPagamento: <?= json_encode($formasPagamento) ?>,
            caixa_id: <?= json_encode($caixaAberto->id) ?>,
            novoPgto: {
                forma_id: '',
                parcelas: 1,
                valor: '0,00'
            },
            searchTimeout: null,
            processando: false
        }
    },
    computed: {
        totalAPagar() {
            return this.itensSelecionados.reduce((acc, item) => acc + item.total_pagar, 0);
        },
        totalRecebido() {
            return this.pagamentosAdicionados.reduce((acc, pgto) => acc + pgto.valor, 0);
        },
        troco() {
            return this.totalRecebido - this.totalAPagar;
        }
    },
    methods: {
        // Formatação
        converterParaBrasileiro(valor) {
            if (valor === undefined || valor === null) return '0,00';
            return parseFloat(valor).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        },
        formatarDataBR(dataStr) {
            if (!dataStr) return '';
            const data = new Date(dataStr + 'T12:00:00');
            return data.toLocaleDateString('pt-BR');
        },
        valorStrToFloat(str) {
            if (!str) return 0;
            if (typeof str === 'number') return str;
            return parseFloat(str.replace(/\./g, '').replace(',', '.'));
        },
        
        // Busca
        debouncedSearch() {
            clearTimeout(this.searchTimeout);
            if (this.query.length < 2) {
                this.resultados = [];
                return;
            }
            this.searchTimeout = setTimeout(this.buscar, 500);
        },
        async buscar() {
            if (!this.query) return;
            try {
                const resp = await fetch(`${URLS.search}?q=${encodeURIComponent(this.query)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (resp.ok) {
                    const jsonResp = await resp.json();
                    
                    // doAdvancedSearch returns { data: [...], paginator: {...} }
                    const resultsArray = jsonResp.data || [];
                    
                    // Filtra os que já estão selecionados
                    const idsSelecionados = this.itensSelecionados.map(i => i.id);
                    this.resultados = resultsArray.filter(item => !idsSelecionados.includes(item.id));
                }
            } catch (e) {
                console.error('Busca falhou', e);
            }
        },
        selecionarLancamento(item) {
            const val = parseFloat(item.valor) || 0;
            const desc = parseFloat(item.desconto) || 0;
            
            this.itensSelecionados.push({
                ...item,
                valor: val,
                desconto_raw: desc,
                desconto: this.converterParaBrasileiro(desc),
                total_pagar: Math.max(0, val - desc),
                cliente_nome: item.cliente ? item.cliente.nome : ''
            });
            this.query = '';
            this.resultados = [];
            
            // Auto preencher valor faltante
            this.preencherRestante();
        },
        removerItem(index) {
            this.itensSelecionados.splice(index, 1);
            this.preencherRestante();
        },
        
        // Cálculos e Inputs
        calcTotalItem(item) {
            let descStr = item.desconto.replace(/[^\d]/g, '');
            if(!descStr) descStr = '0';
            while (descStr.length < 3) descStr = '0' + descStr;
            const cents = descStr.slice(-2);
            let ints = descStr.slice(0, -2).replace(/^0+/g, '') || '0';
            // Pular formatacao de milhar por enquanto pra facilitar, só mascara na saida
            
            item.desconto = ints + ',' + cents;
            
            const descFloat = parseFloat(ints + '.' + cents);
            item.desconto_raw = descFloat;
            item.total_pagar = Math.max(0, item.valor - descFloat);
        },
        formatarDesconto(item) {
            if(!item.desconto.includes(',')) item.desconto += ',00';
            this.preencherRestante();
        },
        
        formatNovoPgtoValor(e) {
            let val = e.target.value.replace(/[^\d]/g, '');
            if(!val) val = '0';
            while (val.length < 3) val = '0' + val;
            const cents = val.slice(-2);
            let ints = val.slice(0, -2).replace(/^0+/g, '') || '0';
            if (ints.length > 3) ints = ints.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            this.novoPgto.valor = ints + ',' + cents;
        },
        onBlurNovoPgtoValor() {
            if(!this.novoPgto.valor.includes(',')) this.novoPgto.valor += ',00';
        },
        
        // Pagamentos
        getFormaNome(id) {
            const f = this.listaFormasPagamento.find(x => x.id == id);
            return f ? f.nome : 'Desconhecido';
        },
        preencherRestante() {
            if (this.troco < 0) {
                this.novoPgto.valor = this.converterParaBrasileiro(Math.abs(this.troco));
            } else {
                this.novoPgto.valor = '0,00';
            }
        },
        addPagamento() {
            const valFloat = this.valorStrToFloat(this.novoPgto.valor);
            if (valFloat > 0 && this.novoPgto.forma_id) {
                this.pagamentosAdicionados.push({
                    forma_id: this.novoPgto.forma_id,
                    parcelas: this.novoPgto.parcelas,
                    valor: valFloat
                });
                // reset form
                this.novoPgto.forma_id = '';
                this.novoPgto.parcelas = 1;
                this.preencherRestante();
            }
        },
        removerPagamento(index) {
            this.pagamentosAdicionados.splice(index, 1);
        },
        
        // Finalização
        async finalizarEReceber() {
            if (this.itensSelecionados.length === 0) return alert('Selecione os itens a pagar.');
            if (this.troco < 0) return alert('Valor recebido é menor que o total a pagar.');
            
            this.processando = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrfToken"]')?.getAttribute('content');
                
                const payload = {
                    lancamentos: this.itensSelecionados.map(i => ({
                        id: i.id,
                        desconto: i.desconto_raw,
                        valor_pago: i.total_pagar
                    })),
                    pagamentos: this.pagamentosAdicionados.map(p => ({
                        forma_pagamento_id: p.forma_id,
                        parcelas: p.parcelas,
                        valor: p.valor,
                        caixa_id: this.caixa_id,
                      
                    })),
                    troco: this.troco > 0 ? this.troco : 0
                };
                
                const resp = await fetch(URLS.checkout, {
    method: 'POST',
    body: (() => {
        const formData = new FormData();
        formData.append('_csrfToken', csrfToken);
        formData.append('lancamentos', JSON.stringify(payload.lancamentos));
        formData.append('pagamentos', JSON.stringify(payload.pagamentos));
        formData.append('troco', payload.troco);
        return formData;
    })(),
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
    }
});
                
                if (resp.ok) {
                    const data = await resp.json();
                    if (data.success) {
                        // Salvar os itens finalizados para o comprovante ANTES de resetar
                        const itensParaComprovante = [...this.itensSelecionados];
                        const totalCalculado = this.totalAPagar;
                        const subtotalCalculado = this.itensSelecionados.reduce((acc, item) => acc + item.valor, 0);
                        const descontoTotal = this.itensSelecionados.reduce((acc, item) => acc + item.desconto_raw, 0);

                        // Reset form state so UI is clean Behind the alert
                        this.itensSelecionados = [];
                        this.pagamentosAdicionados = [];
                        this.novoPgto.valor = '0,00';
                        this.query = '';

                        // SweetAlert2 de Sucesso
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Pagamento',
                                text: 'Pagamento realizado com sucesso.',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Imprimir comprovante',
                                cancelButtonText: 'Ok',
                                confirmButtonColor: '#00d2ff', // Cyan
                                cancelButtonColor: '#00d2a1', // Green
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.imprimirComprovante(itensParaComprovante, subtotalCalculado, descontoTotal, totalCalculado);
                                }
                            });
                        } else {
                            alert('Pagamento finalizado com sucesso!');
                        }

                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Erro', data.message || 'Problema ao processar caixa.', 'error');
                        } else {
                            alert('Erro: ' + (data.message || 'Problema ao processar caixa.'));
                        }
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Erro', 'Erro de conexão ou servidor.', 'error');
                    } else {
                        alert('Erro de conexão ou servidor.');
                    }
                }
            } catch (e) {
                console.error(e);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Erro', 'Ocorreu um erro interno.', 'error');
                } else {
                    alert('Ocorreu um erro.');
                }
            } finally {
                this.processando = false;
            }
        },
        imprimirComprovante(itens, subtotal, desconto, totalPago) {
            const clienteName = itens.length > 0 && itens[0].cliente_nome ? itens[0].cliente_nome : 'Cliente Não Informado';
            
            // Dados Estabelecimento e Usuario do PHP
            const estabNome = <?= json_encode($this->request->getSession()->read('Auth.Estabelecimento.nome_fantasia') ?? 'Nome da Empresa') ?>;
            const estabEnd = <?= json_encode(($this->request->getSession()->read('Auth.Estabelecimento.endereco') ?? '') . ' Nº ' . ($this->request->getSession()->read('Auth.Estabelecimento.numero') ?? '') . ' - ' . ($this->request->getSession()->read('Auth.Estabelecimento.bairro') ?? '') . ' - ' . ($this->request->getSession()->read('Auth.Estabelecimento.cidade') ?? '') . '/' . ($this->request->getSession()->read('Auth.Estabelecimento.estado') ?? '')) ?>;
            const estabTell = <?= json_encode($this->request->getSession()->read('Auth.Estabelecimento.telefone') ?? '') ?>;
            const usuarioNome = <?= json_encode($this->request->getSession()->read('Auth.nome') ?? $this->request->getSession()->read('Auth.Usuario.nome') ?? 'Usuário') ?>;
            
            const dataHora = new Date().toLocaleString('pt-BR');

            // Monte o HTML para a janela de impressão
            let html = `
            <!DOCTYPE html>
            <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <title>Comprovante</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 10px; width: 300px; color: #000; }
                    .center { text-align: center; }
                    .right { text-align: right; }
                    .bold { font-weight: bold; }
                    .linha { border-top: 1px dashed #000; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 5px; margin-bottom: 5px; }
                    th, td { padding: 2px 0; }
                    th { text-align: left; }
                </style>
            </head>
            <body>
                <div class="center bold" style="font-size: 16px; margin-bottom:5px;">${estabNome}</div>
                <div class="center" style="font-size: 10px; margin-bottom: 5px;">
                    ${estabEnd}
                    ${estabTell ? '<br>Tel: ' + estabTell : ''}
                </div>
                
                <div class="linha"></div>
                <div class="center bold" style="font-size: 16px; margin-top:5px; margin-bottom:10px;">Comprovante de pagamento</div>
                <div class="center" style="margin-bottom:10px;">CLIENTE: ${clienteName}</div>
                
                <div class="center" style="margin-bottom:2px;">Impresso por: ${usuarioNome}</div>
                <div class="center" style="margin-bottom:5px;">${dataHora}</div>
                
                <div class="linha"></div>
                
                <table>
                    <thead>
                        <tr class="bold">
                            <th style="width: 45%;">Produto/Serviço</th>
                            <th class="right" style="width: 20%;">Valor</th>
                            <th class="right" style="width: 15%;">Desc.</th>
                            <th class="right" style="width: 20%;">Total</th>
                        </tr>
                    </thead>
                </table>
                <div class="linha"></div>
                <table>
                    <tbody>`;

            itens.forEach(item => {
                html += `
                        <tr>
                            <td style="width: 45%; word-break: break-all;">${item.descricao}</td>
                            <td class="right" style="width: 20%;">${this.converterParaBrasileiro(item.valor)}</td>
                            <td class="right" style="width: 15%;">${this.converterParaBrasileiro(item.desconto_raw)}</td>
                            <td class="right" style="width: 20%;">${this.converterParaBrasileiro(item.total_pagar)}</td>
                        </tr>`;
            });

            html += `
                    </tbody>
                </table>
                
                <div class="linha"></div>
                
                <table>
                    <tr>
                        <td colspan="3" class="bold right" style="padding-right: 15px; width: 80%;">Subtotal</td>
                        <td class="right" style="width: 20%;">${this.converterParaBrasileiro(subtotal)}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="bold right" style="padding-right: 15px;">Desconto</td>
                        <td class="right">${this.converterParaBrasileiro(desconto)}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="bold right" style="font-size: 14px; padding-right: 15px;">Total pago</td>
                        <td class="right bold" style="font-size: 14px;">${this.converterParaBrasileiro(totalPago)}</td>
                    </tr>
                </table>
                
                <div class="linha"></div>
                <div class="center" style="font-size: 12px; margin-top: 5px;">NÃO É UM DOCUMENTO FISCAL</div>
                
                <script>
                    window.onload = function() {
                        window.print();
                    }
                <\/script>
            </body>
            </html>`;

            const printWind = window.open('', '_blank', 'width=400,height=600');
            printWind.document.write(html);
            printWind.document.close();
        }
    }
});

app.mount('#app-caixa');
</script>
<style>
[v-cloak] { display: none; }
.cursor-pointer { cursor: pointer; }

</style>
