<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package PUC_SP
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function puc_sp_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'puc_sp_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function puc_sp_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'puc_sp_pingback_header');

/**
 * Menu_Default
 */
class Menu_Default extends Walker_Nav_Menu
{
	// Displays start of an element. E.g '<li> Item Name'
	// @see Walker::start_el()
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{
		$object 			= $item->object;
		$type 				= $item->type;
		$title 				= $item->title;
		$description 	= $item->description;
		$permalink 		= $item->url;

		// Verifica se o item está ativo
		$is_active = in_array('current-menu-item', $item->classes) || in_array('current_page_item', $item->classes);

		$output .= "<li class='" .  implode(" ", $item->classes) . "'>";

		$baseClasses = "text-app-black hover:text-app-blue lg:px-4 lg:py-2 text-base tracking-wide transition-colors  border-b-[3px]";
		$activeClasses = $is_active ? "font-bold border-b-app-blue" : "border-b-transparent";
		$classesCSS = $baseClasses . " " . $activeClasses;

		if ($permalink && $permalink != '#') {
			$output .= '<a class="' . $classesCSS . '" href="' . $permalink . '">';
		} else {
			$output .= '<span class="' . $classesCSS . '">';
		}

		$output .= $title;

		if ($description != '' && $depth == 0) {
			$output .= '<small class="">' . $description . '</small>';
		}
		if ($permalink && $permalink != '#') {
			$output .= '</a>';
		} else {
			$output .= '</span>';
		}
	}
}

/**
 * Auto Copyright
 */
function auto_copyright($year = 'auto')
{
	if ($year == 'auto') {
		$year = date('Y');
	}

	if (intval($year) == date('Y')) {
		echo intval($year);
	}

	if (intval($year) < date('Y')) {
		echo intval($year) . ' - ' . date('Y');
	}

	if (intval($year) > date('Y')) {
		echo date('Y');
	}
}

/**
 * Mapa de acentos para normalização de letras
 */
function puc_sp_get_accents_map()
{
	return [
		'Á' => 'A',
		'À' => 'A',
		'Ã' => 'A',
		'Â' => 'A',
		'Ä' => 'A',
		'Å' => 'A',
		'É' => 'E',
		'È' => 'E',
		'Ê' => 'E',
		'Ë' => 'E',
		'Í' => 'I',
		'Ì' => 'I',
		'Î' => 'I',
		'Ï' => 'I',
		'Ó' => 'O',
		'Ò' => 'O',
		'Õ' => 'O',
		'Ô' => 'O',
		'Ö' => 'O',
		'Ú' => 'U',
		'Ù' => 'U',
		'Û' => 'U',
		'Ü' => 'U',
		'Ç' => 'C',
		'Ñ' => 'N',
		'Ý' => 'Y',
	];
}

/**
 * Normaliza a primeira letra de um título para agrupamento alfabético
 * Remove BOM, caracteres de controle e converte acentos
 *
 * @param string $title O título a ser normalizado
 * @return string Letra normalizada (A-Z ou #)
 */
function puc_sp_normalize_first_letter($title)
{
	// Remove BOM e caracteres de controle
	$title_clean = preg_replace('/^\x{FEFF}/u', '', $title);
	$title_clean = preg_replace('/^[\s\x00-\x1F\x7F]+/u', '', $title_clean);

	// Pega a primeira letra e converte para maiúscula
	$first_letter = mb_strtoupper(mb_substr($title_clean, 0, 1, 'UTF-8'), 'UTF-8');

	// Normaliza acentos
	$accents_map = puc_sp_get_accents_map();

	if (isset($accents_map[$first_letter])) {
		return $accents_map[$first_letter];
	} elseif (preg_match('/^[A-Z]$/u', $first_letter)) {
		return $first_letter;
	}

	return '#';
}

/**
 * Busca os autores de um verbete via campo ACF
 * Se não houver autores vinculados diretamente, tenta buscar pelo campo autores_old_id
 *
 * @param int $post_id ID do post (verbete)
 * @return array Array de objetos WP_Term dos autores
 */
function puc_sp_get_verbete_autores($post_id)
{
	$autores_ids = get_field('autores', $post_id);
	$autores = [];

	// Primeiro tenta buscar autores vinculados diretamente
	if ($autores_ids && is_array($autores_ids)) {
		foreach ($autores_ids as $autor_id) {
			$termo = get_term($autor_id);
			if ($termo && !is_wp_error($termo)) {
				$autores[] = $termo;
			}
		}
	}

	// Se não encontrou autores vinculados, tenta buscar pelo old_id
	if (empty($autores)) {
		$autores_old_id = get_field('autores_old_id', $post_id);
		if ($autores_old_id) {
			$autores = puc_sp_get_autores_by_old_id($autores_old_id);
		}
	}

	return $autores;
}

/**
 * Busca os tomos relacionados a um verbete via campo ACF
 *
 * @param int $post_id ID do post (verbete)
 * @return array Array com dados dos tomos (id, title, link)
 */
function puc_sp_get_verbete_tomos($post_id)
{
	$tomos_field = get_field('edicoes', $post_id);
	$tomos = [];

	if ($tomos_field && is_array($tomos_field)) {
		foreach ($tomos_field as $tomo) {
			if (is_object($tomo)) {
				$tomos[] = [
					'id'    => $tomo->ID,
					'title' => get_the_title($tomo->ID),
					'link'  => get_the_permalink($tomo->ID),
				];
			}
		}
	}

	return $tomos;
}

/**
 * Gera links HTML para os autores de um verbete
 *
 * @param array $autores Array de objetos WP_Term
 * @param string $class Classes CSS adicionais para os links
 * @return string HTML com os links separados por vírgula
 */
function puc_sp_render_autores_links($autores, $class = 'hover:text-app-blue transition-colors')
{
	if (empty($autores)) {
		return '';
	}

	$links = array_map(function ($autor) use ($class) {
		$link = get_term_link($autor);
		if (!is_wp_error($link)) {
			return '<a href="' . esc_url($link) . '" class="' . esc_attr($class) . '">' . esc_html($autor->name) . '</a>';
		}
		return esc_html($autor->name);
	}, $autores);

	return implode(', ', $links);
}

/**
 * Gera links HTML para os tomos de um verbete
 *
 * @param array $tomos Array com dados dos tomos
 * @param string $class Classes CSS adicionais para os links
 * @return string HTML com os links separados por vírgula
 */
function puc_sp_render_tomos_links($tomos, $class = 'hover:text-app-blue transition-colors')
{
	if (empty($tomos)) {
		return '';
	}

	$links = array_map(function ($tomo) use ($class) {
		return '<a href="' . esc_url($tomo['link']) . '" class="' . esc_attr($class) . '">' . esc_html($tomo['title']) . '</a>';
	}, $tomos);

	return implode(', ', $links);
}

/**
 * Agrupa um array de itens por letra
 *
 * @param array $items Array de itens com chave 'letter'
 * @return array Array agrupado e ordenado por letra
 */
function puc_sp_group_by_letter($items)
{
	$grouped = [];

	foreach ($items as $item) {
		$letter = $item['letter'];
		if (!isset($grouped[$letter])) {
			$grouped[$letter] = [];
		}
		$grouped[$letter][] = $item;
	}

	ksort($grouped);

	return $grouped;
}

/**
 * Busca autores na taxonomia pelo campo old_id (ID legado)
 * Usado como fallback quando o verbete não tem autores vinculados diretamente,
 * mas possui o campo autores_old_id preenchido
 *
 * @param string|array $old_ids ID(s) legado(s) dos autores (pode ser string separada por vírgula ou array)
 * @return array Array de objetos WP_Term dos autores encontrados
 */
function puc_sp_get_autores_by_old_id($old_ids)
{
	$autores = [];

	if (empty($old_ids)) {
		return $autores;
	}

	// Normaliza para array
	if (is_string($old_ids)) {
		$old_ids = array_map('trim', explode(',', $old_ids));
	}

	if (!is_array($old_ids)) {
		$old_ids = [$old_ids];
	}

	foreach ($old_ids as $old_id) {
		if (empty($old_id)) {
			continue;
		}

		// Busca o termo da taxonomia 'autor' que tem o meta 'old_id' igual ao valor
		$terms = get_terms([
			'taxonomy'   => 'autor',
			'hide_empty' => false,
			'meta_query' => [
				[
					'key'   => 'old_id',
					'value' => $old_id,
				],
			],
		]);

		if (!is_wp_error($terms) && !empty($terms)) {
			foreach ($terms as $term) {
				// Evita duplicatas
				$exists = false;
				foreach ($autores as $autor) {
					if ($autor->term_id === $term->term_id) {
						$exists = true;
						break;
					}
				}
				if (!$exists) {
					$autores[] = $term;
				}
			}
		}
	}

	return $autores;
}

/**
 * Busca um tomo (post type 'tomos') pelo ID legado
 * Usado como fallback quando o verbete não tem edições vinculadas diretamente,
 * mas possui um ID de tomo antigo
 *
 * @param string|int $old_id ID legado do tomo
 * @return array Array de objetos WP_Post dos tomos encontrados
 */
function puc_sp_get_tomos_by_old_id($old_id)
{
	$tomos = [];

	if (empty($old_id)) {
		return $tomos;
	}

	// Busca o tomo que tem o meta 'old_id' igual ao valor
	$query = new WP_Query([
		'post_type'      => 'tomos',
		'posts_per_page' => -1,
		'meta_query'     => [
			[
				'key'   => 'old_id',
				'value' => $old_id,
			],
		],
	]);

	if ($query->have_posts()) {
		$tomos = $query->posts;
	}

	wp_reset_postdata();

	return $tomos;
}

/**
 * Busca os autores de um verbete, com fallback para old_id
 * Primeiro tenta pelo campo 'autores', se vazio usa 'autores_old_id'
 *
 * @param int $post_id ID do post (verbete)
 * @return array Array de objetos WP_Term dos autores
 */
function puc_sp_get_verbete_autores_with_fallback($post_id)
{
	// Primeiro tenta buscar autores pelo campo normal
	$autores = puc_sp_get_verbete_autores($post_id);

	// Se não encontrou, tenta pelo old_id
	if (empty($autores)) {
		$autores_old_id = get_field('autores_old_id', $post_id);
		if (!empty($autores_old_id)) {
			$autores = puc_sp_get_autores_by_old_id($autores_old_id);
		}
	}

	return $autores;
}

/**
 * Busca as edições/tomos de um verbete, com fallback para old_id
 * Primeiro tenta pelo campo 'edicoes', se vazio usa 'tomo_old_id' ou similar
 *
 * @param int $post_id ID do post (verbete)
 * @return array Array de objetos WP_Post dos tomos
 */
function puc_sp_get_verbete_edicoes_with_fallback($post_id)
{
	// Primeiro tenta buscar edições pelo campo normal
	$edicoes = get_field('edicoes', $post_id);

	if (!empty($edicoes) && is_array($edicoes)) {
		return $edicoes;
	}

	// Se não encontrou, tenta pelo tomo_old_id (que é só o ID do tomo antigo)
	$tomo_old_id = get_field('tomo_old_id', $post_id);
	if (!empty($tomo_old_id)) {
		return puc_sp_get_tomos_by_old_id($tomo_old_id);
	}

	return [];
}

/**
 * Conta quantos verbetes um autor possui
 * Busca via meta_query no campo ACF 'autores' (array serializado)
 *
 * @param int $term_id ID do termo (autor)
 * @return int Número de verbetes
 */
function puc_sp_count_autor_verbetes($term_id)
{
	$query = new WP_Query([
		'post_type'      => 'verbetes',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => [
			[
				'key'     => 'autores',
				'value'   => '"' . $term_id . '"',
				'compare' => 'LIKE',
			],
		],
	]);

	return $query->found_posts;
}

/**
 * Busca dados (código e coordenadores) da versão ativa de um Tomo.
 * Lê o campo 'versao_para_apresentar_no_site' (taxonomia edicoes) e usa
 * esse valor para filtrar o repetidor 'versao_e_codigo', retornando
 * apenas a linha cuja 'versao' corresponde à versão ativa.
 *
 * @param int $tomo_id ID do post (tomo)
 * @return array{codigo: string, coordenadores: string, versao: WP_Term|null}
 */
function puc_sp_get_tomo_versao_ativa($tomo_id)
{
	$default = [
		'codigo'        => '',
		'coordenadores' => '',
		'versao'        => null,
	];

	$versao_ativa = get_field('versao_para_apresentar_no_site', $tomo_id);
	$versao_ativa_id = puc_sp_get_term_id($versao_ativa);

	if (!$versao_ativa_id) {
		return $default;
	}

	$linhas = get_field('versao_e_codigo', $tomo_id);

	if (empty($linhas) || !is_array($linhas)) {
		return $default;
	}

	foreach ($linhas as $linha) {
		$linha_versao_id = puc_sp_get_term_id($linha['versao'] ?? null);

		if ($linha_versao_id && $linha_versao_id === $versao_ativa_id) {
			return [
				'codigo'        => $linha['codigo'] ?? '',
				'coordenadores' => $linha['coordenadores'] ?? '',
				'versao'        => get_term($versao_ativa_id),
			];
		}
	}

	return $default;
}

/**
 * Normaliza valor de campo ACF taxonomia (termo, ID ou array) para o ID do termo.
 *
 * @param mixed $valor
 * @return int|null
 */
function puc_sp_get_term_id($valor)
{
	if (empty($valor)) {
		return null;
	}

	if (is_array($valor)) {
		$valor = reset($valor);
	}

	if ($valor instanceof WP_Term) {
		return (int) $valor->term_id;
	}

	if (is_numeric($valor)) {
		return (int) $valor;
	}

	return null;
}

/**
 * Normaliza valor de campo ACF de arquivo/imagem (URL, array ou ID) para URL.
 *
 * @param mixed $valor
 * @return string
 */
function puc_sp_get_field_url($valor)
{
	if (empty($valor)) {
		return '';
	}

	if (is_array($valor)) {
		return $valor['url'] ?? '';
	}

	if (is_numeric($valor)) {
		return wp_get_attachment_url($valor) ?: '';
	}

	return (string) $valor;
}

/**
 * Busca o PDF correspondente à edição ativa do tomo relacionado a um verbete.
 * Cada verbete tem um repetidor 'edicao_e_pdf' (sub-campos 'edicao' e 'versao_pdf')
 * que relaciona cada edição/versão da taxonomia 'edicoes' a um arquivo PDF.
 * O tomo relacionado ao verbete define, em 'versao_para_apresentar_no_site',
 * qual edição está ativa no site — usamos esse valor para escolher a linha certa.
 * Se não houver correspondência no repetidor, cai para os campos legados
 * 'versao_pdf' e 'edicao' (diretos, não repetidor) mantidos no verbete.
 *
 * @param int $post_id ID do post (verbete)
 * @return array{pdf_url: string, edicao: WP_Term|null}
 */
function puc_sp_get_verbete_pdf_ativo($post_id)
{
	$default = [
		'pdf_url' => '',
		'edicao'  => null,
	];

	$tomos = puc_sp_get_verbete_edicoes_with_fallback($post_id);
	$tomo = !empty($tomos) ? reset($tomos) : null;

	$versao_ativa_id = $tomo ? puc_sp_get_term_id(get_field('versao_para_apresentar_no_site', $tomo->ID)) : null;

	if ($versao_ativa_id) {
		$linhas = get_field('edicao_e_pdf', $post_id);

		if (!empty($linhas) && is_array($linhas)) {
			foreach ($linhas as $linha) {
				$linha_edicao_id = puc_sp_get_term_id($linha['edicao'] ?? null);

				if ($linha_edicao_id && $linha_edicao_id === $versao_ativa_id) {
					return [
						'pdf_url' => puc_sp_get_field_url($linha['versao_pdf'] ?? null),
						'edicao'  => get_term($versao_ativa_id),
					];
				}
			}
		}
	}

	// Fallback: campos legados (verbetes ainda não migrados para o repetidor)
	$pdf_url_legado = puc_sp_get_field_url(get_field('versao_pdf', $post_id));
	$edicao_legada = get_field('edicao', $post_id);
	$edicao_legada_id = puc_sp_get_term_id($edicao_legada);

	if ($pdf_url_legado || $edicao_legada_id) {
		return [
			'pdf_url' => $pdf_url_legado,
			'edicao'  => $edicao_legada_id ? get_term($edicao_legada_id) : null,
		];
	}

	return $default;
}
