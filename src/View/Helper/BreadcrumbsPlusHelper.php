<?php
namespace App\View\Helper;

use Cake\View\Helper;

class BreadcrumbsPlusHelper extends Helper
{
public array $helpers = ['Breadcrumbs'];

protected ?string $firstCrumbTitle = null;
protected ?array $firstCrumbUrl = null;
protected array $firstCrumbOptions = [];

    /**
     * Define o primeiro breadcrumb (breadcrumb inicial).
     * Aceita string ou array para título.
     */
    public function setFirstCrumb(string|array $title, array $url = [], array $options = []): void
    {
        $crumbs = $this->Breadcrumbs->getCrumbs();

        // Se $title for array com chaves 'title' e 'url', usa direto
        if (is_array($title) && isset($title['title']) && isset($title['url'])) {
            $newTitle = $title['title'];
            $newUrl = $title['url'];
            $newOptions = $options;
            // Se o array $title tiver 'options', mescla
            if (isset($title['options']) && is_array($title['options'])) {
                $newOptions = array_merge($options, $title['options']);
            }
        } else {
            $newTitle = $title;
            $newUrl = $url;
            $newOptions = $options;
        }

        $firstTitle = !empty($crumbs) && isset($crumbs[0]['title'])
            ? $this->titleToString($crumbs[0]['title'])
            : '';

        $titleToCompare = $this->titleToString($newTitle);

        if ($firstTitle === $titleToCompare) {
            return; // Já existe breadcrumb inicial igual, sai
        }

        $this->firstCrumbTitle = $newTitle;
        $this->firstCrumbUrl = $newUrl;
        $this->firstCrumbOptions = $newOptions;

        $this->applyFirstCrumb();
    }

    /**
     * Garante que o breadcrumb inicial esteja no começo da lista.
     */
    protected function applyFirstCrumb(): void
    {
        $crumbs = $this->Breadcrumbs->getCrumbs();

        $titleWithIcon = $this->firstCrumbTitle; // Se quiser, aqui pode concatenar ícone

        array_unshift($crumbs, [
            'title' => $titleWithIcon,
            'url' => $this->firstCrumbUrl,
            'options' => $this->firstCrumbOptions + ['escape' => true], // escape true para evitar HTML bruto
        ]);

        $this->Breadcrumbs->reset()->add($crumbs);
    }

/**
 * Renderiza o breadcrumb. Se não existir breadcrumb inicial, adiciona padrão "Início".
 */
public function render(array $options = []): string
{
    $crumbs = $this->Breadcrumbs->getCrumbs();

    if (empty($crumbs)) {
        $this->setFirstCrumb('Início', ['controller' => 'Empresas', 'action' => 'index']);
    }

    $defaultOptions = [
        'firstClass' => false,
        'lastClass' => 'active',
        'class' => 'breadcrumb-item',
    ];

    $options = array_merge($defaultOptions, $options);

    return $this->Breadcrumbs->render($options);
}

/**
 * Wrapper para adicionar breadcrumbs extras.
 */
    public function add(string|array $title, $url = null): void
    {
        $this->Breadcrumbs->add($title, $url);
    }

    /**
     * Converte título (string|array) para string limpa para comparação e saída.
     */
    protected function titleToString(string|array|null $title): string
    {
        if ($title === null) {
            return '';
        }

        if (is_array($title)) {
            // Só pega os valores escalares e ignora arrays internos
            $strings = [];
            foreach ($title as $item) {
                if (is_scalar($item)) {
                    $strings[] = (string)$item;
                }
            }
            return implode(' ', $strings);
        }

        return (string)$title;
    }

}
