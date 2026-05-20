import sys

filename = '/opt/cakephp-app/painel/templates/Consultas/editar.php'
with open(filename, 'r', encoding='utf-8') as f:
    content = f.read()

replacements = [
    ("""<a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${anamnese.id}`\"""", 
     """<a href="#" @click.prevent="abrirModalAssinarPaciente(anamnese)\""""),
    ("""<a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${anotacao.id}`\"""", 
     """<a href="#" @click.prevent="abrirModalAssinarPaciente(anotacao)\""""),
    ("""<a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${doc.id}`\"""", 
     """<a href="#" @click.prevent="abrirModalAssinarPaciente(doc)\""""),
    ("""<a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${laudo.id}`\"""", 
     """<a href="#" @click.prevent="abrirModalAssinarPaciente(laudo)\""""),
]

for old, new in replacements:
    content = content.replace(old, new)

with open(filename, 'w', encoding='utf-8') as f:
    f.write(content)
print('Replacements done in PHP template')
