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

A modificação é feita na tabela **table**:*ca_occurrences* no tipo **Type**:*exposição*. 
O Plugin faz uma leitura do texto armazenado no parâmetro **prevParameter**:*tipo_exposicao*; esse 
valor é comparado com os valores *prev* em **values**;  se for igual, no parâmetro **nextParameter**;*madeMACUSP*
é salvado o valor *next* (definido em **values**). 

## Modo Roboto (automático)
Nesse modo é gerada una lista de todas as **ca_ocurrences** tipo **exposição** e uma button "Roboto", 
Para cada exposição, ele atualiza o valor do parâmetro *madeMACUSP* dependendo do valor do parâmetro "tipo_exposicao". 

## Modo Providence - plugin
Cada fez que for editado uma exposição no PROVIDENCE, é corroborado o parâmetro *tipo de exposicao*, se ele for modificado então 
o parâmetro *madeMACUSP* é atualizado. 




