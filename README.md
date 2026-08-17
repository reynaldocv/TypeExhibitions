# Funcionamento

## Requerimentos
Deve ser criado uma parâmetro usando um vocabulario. 
Por exemplo, nós criamos a parâmetro  **madeMACUSP** para a tabela *ca_occurrences*. O parâmetro **madeMACUSP**
usa um seguinte vocabulario: 
 - MACUSP Exhibitions
 - Exhibitions from other institutions

 ## Arquivo config

        change = {
            table = "ca_occurrences", 
            type = "exposição", 
            prevParameter = "tipo_exposicao", 
            nextParameter = "madeMACUSP", 
            values = {        
                first = {
                    prev = "Exposição produzida pelo MAC USP", 
                    next = "MACUSP Exhibitions",
                    }, 
                second = {            
                    prev = "Exposição anterior à incorporação ao MAC USP", 
                    next = "Exhibitions from other institutions",  
                }
                third = {
                    prev = "Exposição externa (empréstimo)",
                    next = "Exhibitions from other institutions",  
                }, 
            },
        }

A modificação é feita na tabela **table**: *ca_occurrences*, no tipo **Type**: *exposição*.

O plugin faz uma leitura do texto armazenado no parâmetro **prevParameter**: *tipo_exposicao*. Esse valor é comparado com os valores **prev** em values; se for igual, o valor **next** (definido em values) é salvo no parâmetro **nextParameter**: *madeMACUSP*.

## Modo Roboto (automático)
Neste modo, é gerada uma lista de todas as **ca_occurrences** do **tipo exposição** e um botão "Roboto". Para cada exposição, o sistema atualiza o valor do parâmetro **madeMACUSP** dependendo do valor do parâmetro **tipo_exposicao**.

## Modo Providence - plugin
Cada vez que uma exposição for editada no Providence, o parâmetro tipo_exposicao é verificado. Se ele tiver sido modificado, o parâmetro madeMACUSP será atualizado.

