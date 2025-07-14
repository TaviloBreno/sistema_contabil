<?xml version="1.0" encoding="UTF-8"?>
<NFe xmlns="http://www.portalfiscal.inf.br/nfe">
    <infNFe Id="NFe{{ $notaFiscal->chave_acesso }}">
        <ide>
            <cUF>{{ substr($notaFiscal->empresa->cnpj, 0, 2) }}</cUF>
            <cNF>{{ rand(10000000, 99999999) }}</cNF>
            <natOp>{{ $notaFiscal->tipo == 'saida' ? 'Venda' : 'Compra' }}</natOp>
            <mod>{{ $notaFiscal->modelo }}</mod>
            <serie>{{ $notaFiscal->serie }}</serie>
            <nNF>{{ $notaFiscal->numero_nf }}</nNF>
            <dhEmi>{{ $notaFiscal->data_emissao->format('Y-m-d\TH:i:s-03:00') }}</dhEmi>
            <tpNF>{{ $notaFiscal->tipo == 'saida' ? '1' : '0' }}</tpNF>
            <idDest>1</idDest>
            <cMunFG>3550308</cMunFG>
            <tpImp>1</tpImp>
            <tpEmis>1</tpEmis>
            <cDV>{{ substr($notaFiscal->chave_acesso, -1) }}</cDV>
            <tpAmb>2</tpAmb>
            <finNFe>1</finNFe>
            <indFinal>1</indFinal>
            <indPres>1</indPres>
        </ide>

        <emit>
            <CNPJ>{{ preg_replace('/\D/', '', $notaFiscal->empresa->cnpj) }}</CNPJ>
            <xNome>{{ $notaFiscal->empresa->razao_social }}</xNome>
            <enderEmit>
                <xLgr>Rua Principal</xLgr>
                <nro>123</nro>
                <xBairro>Centro</xBairro>
                <cMun>3550308</cMun>
                <xMun>São Paulo</xMun>
                <UF>SP</UF>
                <CEP>01000000</CEP>
                <cPais>1058</cPais>
                <xPais>Brasil</xPais>
            </enderEmit>
            <IE>123456789</IE>
            <CRT>{{ $notaFiscal->empresa->regime_tributario == 'Simples Nacional' ? '1' : '3' }}</CRT>
        </emit>

        <dest>
            <xNome>{{ $notaFiscal->destinatario_nome }}</xNome>
            @if(strlen(preg_replace('/\D/', '', $notaFiscal->destinatario_documento)) == 11)
                <CPF>{{ preg_replace('/\D/', '', $notaFiscal->destinatario_documento) }}</CPF>
            @else
                <CNPJ>{{ preg_replace('/\D/', '', $notaFiscal->destinatario_documento) }}</CNPJ>
            @endif
            @if($notaFiscal->destinatario_endereco)
            <enderDest>
                <xLgr>{{ $notaFiscal->destinatario_endereco }}</xLgr>
                <nro>S/N</nro>
                <xBairro>Centro</xBairro>
                @if($notaFiscal->destinatario_cidade)
                <xMun>{{ $notaFiscal->destinatario_cidade }}</xMun>
                @endif
                @if($notaFiscal->destinatario_uf)
                <UF>{{ $notaFiscal->destinatario_uf }}</UF>
                @endif
                @if($notaFiscal->destinatario_cep)
                <CEP>{{ preg_replace('/\D/', '', $notaFiscal->destinatario_cep) }}</CEP>
                @endif
                <cPais>1058</cPais>
                <xPais>Brasil</xPais>
            </enderDest>
            @endif
        </dest>

        @foreach($notaFiscal->itens as $item)
        <det nItem="{{ $item->numero_item }}">
            <prod>
                <cProd>{{ $item->codigo_produto }}</cProd>
                <cEAN></cEAN>
                <xProd>{{ $item->descricao }}</xProd>
                @if($item->ncm)
                <NCM>{{ $item->ncm }}</NCM>
                @endif
                <CFOP>{{ $item->cfop }}</CFOP>
                <uCom>{{ $item->unidade }}</uCom>
                <qCom>{{ number_format($item->quantidade, 4, '.', '') }}</qCom>
                <vUnCom>{{ number_format($item->valor_unitario, 2, '.', '') }}</vUnCom>
                <vProd>{{ number_format($item->valor_total, 2, '.', '') }}</vProd>
                <cEANTrib></cEANTrib>
                <uTrib>{{ $item->unidade }}</uTrib>
                <qTrib>{{ number_format($item->quantidade, 4, '.', '') }}</qTrib>
                <vUnTrib>{{ number_format($item->valor_unitario, 2, '.', '') }}</vUnTrib>
            </prod>

            <imposto>
                @if($item->icms_valor > 0)
                <ICMS>
                    <ICMS00>
                        <orig>0</orig>
                        <CST>{{ $item->icms_cst ?? '00' }}</CST>
                        <modBC>3</modBC>
                        <vBC>{{ number_format($item->icms_base_calculo, 2, '.', '') }}</vBC>
                        <pICMS>{{ number_format($item->icms_aliquota, 2, '.', '') }}</pICMS>
                        <vICMS>{{ number_format($item->icms_valor, 2, '.', '') }}</vICMS>
                    </ICMS00>
                </ICMS>
                @else
                <ICMS>
                    <ICMS60>
                        <orig>0</orig>
                        <CST>60</CST>
                    </ICMS60>
                </ICMS>
                @endif

                @if($item->ipi_valor > 0)
                <IPI>
                    <cEnq>999</cEnq>
                    <IPITrib>
                        <CST>{{ $item->ipi_cst ?? '50' }}</CST>
                        <vBC>{{ number_format($item->ipi_base_calculo, 2, '.', '') }}</vBC>
                        <pIPI>{{ number_format($item->ipi_aliquota, 2, '.', '') }}</pIPI>
                        <vIPI>{{ number_format($item->ipi_valor, 2, '.', '') }}</vIPI>
                    </IPITrib>
                </IPI>
                @endif

                <PIS>
                    @if($item->pis_valor > 0)
                    <PISAliq>
                        <CST>{{ $item->pis_cst ?? '01' }}</CST>
                        <vBC>{{ number_format($item->pis_base_calculo, 2, '.', '') }}</vBC>
                        <pPIS>{{ number_format($item->pis_aliquota, 4, '.', '') }}</pPIS>
                        <vPIS>{{ number_format($item->pis_valor, 2, '.', '') }}</vPIS>
                    </PISAliq>
                    @else
                    <PISNT>
                        <CST>08</CST>
                    </PISNT>
                    @endif
                </PIS>

                <COFINS>
                    @if($item->cofins_valor > 0)
                    <COFINSAliq>
                        <CST>{{ $item->cofins_cst ?? '01' }}</CST>
                        <vBC>{{ number_format($item->cofins_base_calculo, 2, '.', '') }}</vBC>
                        <pCOFINS>{{ number_format($item->cofins_aliquota, 4, '.', '') }}</pCOFINS>
                        <vCOFINS>{{ number_format($item->cofins_valor, 2, '.', '') }}</vCOFINS>
                    </COFINSAliq>
                    @else
                    <COFINSNT>
                        <CST>08</CST>
                    </COFINSNT>
                    @endif
                </COFINS>
            </imposto>
        </det>
        @endforeach

        <total>
            <ICMSTot>
                <vBC>{{ number_format($notaFiscal->itens->sum('icms_base_calculo'), 2, '.', '') }}</vBC>
                <vICMS>{{ number_format($notaFiscal->valor_icms, 2, '.', '') }}</vICMS>
                <vICMSDeson>0.00</vICMSDeson>
                <vFCP>0.00</vFCP>
                <vBCST>0.00</vBCST>
                <vST>0.00</vST>
                <vFCPST>0.00</vFCPST>
                <vFCPSTRet>0.00</vFCPSTRet>
                <vProd>{{ number_format($notaFiscal->valor_produtos, 2, '.', '') }}</vProd>
                <vFrete>{{ number_format($notaFiscal->valor_frete, 2, '.', '') }}</vFrete>
                <vSeg>{{ number_format($notaFiscal->valor_seguro, 2, '.', '') }}</vSeg>
                <vDesc>{{ number_format($notaFiscal->valor_desconto, 2, '.', '') }}</vDesc>
                <vII>0.00</vII>
                <vIPI>{{ number_format($notaFiscal->valor_ipi, 2, '.', '') }}</vIPI>
                <vIPIDevol>0.00</vIPIDevol>
                <vPIS>{{ number_format($notaFiscal->valor_pis, 2, '.', '') }}</vPIS>
                <vCOFINS>{{ number_format($notaFiscal->valor_cofins, 2, '.', '') }}</vCOFINS>
                <vOutro>{{ number_format($notaFiscal->valor_outras_despesas, 2, '.', '') }}</vOutro>
                <vNF>{{ number_format($notaFiscal->valor_total, 2, '.', '') }}</vNF>
                <vTotTrib>0.00</vTotTrib>
            </ICMSTot>
        </total>

        <transp>
            <modFrete>9</modFrete>
        </transp>

        <pag>
            <detPag>
                <indPag>0</indPag>
                <tPag>99</tPag>
                <vPag>{{ number_format($notaFiscal->valor_total, 2, '.', '') }}</vPag>
            </detPag>
        </pag>

        @if($notaFiscal->observacoes)
        <infAdic>
            <infCpl>{{ $notaFiscal->observacoes }}</infCpl>
        </infAdic>
        @endif
    </infNFe>

    @if($notaFiscal->status === 'autorizada')
    <Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
        <SignedInfo>
            <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
            <SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
            <Reference URI="#NFe{{ $notaFiscal->chave_acesso }}">
                <Transforms>
                    <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
                    <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                </Transforms>
                <DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                <DigestValue>DIGEST_VALUE_PLACEHOLDER</DigestValue>
            </Reference>
        </SignedInfo>
        <SignatureValue>SIGNATURE_VALUE_PLACEHOLDER</SignatureValue>
        <KeyInfo>
            <X509Data>
                <X509Certificate>CERTIFICATE_PLACEHOLDER</X509Certificate>
            </X509Data>
        </KeyInfo>
    </Signature>
    @endif
</NFe>
