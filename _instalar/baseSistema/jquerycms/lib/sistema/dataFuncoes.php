<?php

function dataCarregar($Conexao, $Tabela, $IdNomeCampo, $IdValor) {
    try {

        $sql = "SELECT * FROM `$Tabela` where `$IdNomeCampo` = :codigo LIMIT 0, 1";

        $statement = $Conexao->prepare($sql);
        $statement->bindValue(':codigo', $IdValor);

        $statement->execute();
        $dados = $statement->fetch(PDO::FETCH_ASSOC);

        if (isset($dados[$IdNomeCampo]))
            return $dados;
        else
            return false;
    } catch (Exception $exc) {
        throw new jquerycmsException("Problema para carregar sql. {$exc->getMessage()}. ");
    }
}

function dataDeletar($Conexao, $Tabela, $IdNomeCampo, $IdValor) {
    try {

        $sql = "DELETE FROM `$Tabela` where `$IdNomeCampo` = :codigo";

        $statement = $Conexao->prepare($sql);
        $statement->bindValue(':codigo', $IdValor);

        $retorno = $statement->execute();

        if ($retorno && ($statement->rowCount() == 1)) {
            return true;
        } else {
            $erroInfo = $statement->errorInfo();
            if (isset($erroInfo[2])) {
                if (str_contains($erroInfo[2], "Cannot delete or update a parent row: a foreign key")) {
                    throw new jquerycmsException("É provável que este registro esteja associado a outra tabela. <a href='#' rel='tooltip' title='{$erroInfo[2]}'>(?)</a>");
                } else {
                    throw new jquerycmsException("Problema para deletar. {$erroInfo[2]}. ");
                }
            } else {
                throw new jquerycmsException("Problema para deletar. O registro nao foi inserido, será necessario debugar o codigo. ");
            }
        }
    } catch (Exception $exc) {
        throw new jquerycmsException("Problema para deletar sql. {$exc->getMessage()}. ");
    }
}

function dataListar($Conexao, $tabela, $where = "", $orderBy = "", $limit = "", $select = "*", $leftjoin = "") {
    try {

        if ($where instanceof dataFilter) {
            $wherestr = "WHERE {$where->SqlParametrized()}";
        } elseif ($where) {
            $wherestr = "WHERE $where";
        } else {
            $wherestr = "";
        }

        if ($orderBy instanceof dataOrder) {
            $orderBystr = $orderBy->SqlOrder();
            if ($orderBystr) {
                $orderBystr = "ORDER BY " . $orderBystr;
            }
        } elseif ($orderBy) {
            $orderBystr = "ORDER BY " . $orderBy;
        } else {
            $orderBystr = "";
        }


        if ($limit) {
            $limitstr = "LIMIT :limit1, :limit2";
        } else {
            $limitstr = "";
        }

        $sql = "SELECT $select FROM `$tabela` $leftjoin $wherestr $orderBystr $limitstr";
        $statement = $Conexao->prepare($sql);

        if ($where instanceof dataFilter) {
            $statement = $where->SqlBindOnStatement($statement);
        }

        if ($limit) {
            $limit = explode(",", $limit);

            if (!isset($limit[0]) || !isset($limit[1])) {
                throw new jquerycmsException("Parametro limit inválido. Deve ser dois inteiros separados por virgula. ex.: 10,30");
            }
            $statement->bindValue(':limit1', intval($limit[0]), PDO::PARAM_INT);
            $statement->bindValue(':limit2', intval($limit[1]), PDO::PARAM_INT);
        }

        $statement->execute();

        $erroInfo = $statement->errorInfo();
        if (isset($erroInfo[2])) {
            throw new jquerycmsException("Problema para listar. {$erroInfo[2]}. ");
        }

        $dados = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $dados;
    } catch (Exception $exc) {
        throw new jquerycmsException("Problema para listar sql. {$exc->getMessage()}. ");
    }
}

function dataExecSqlDireto($Conexao, $sql, $fetchAll = true) {
    try {

        $statement = $Conexao->prepare($sql);
        $statement->execute();
        if ($fetchAll)
            $dados = $statement->fetchAll(PDO::FETCH_ASSOC);
        else
            $dados = $statement->fetch(PDO::FETCH_ASSOC);

        if ($dados && ($dados != ""))
            return $dados;
        else
            return true;
    } catch (Exception $exc) {
        throw new jquerycmsException("Problema para executar sql. {$exc->getMessage()}. ");
    }
}

class dataFilter {

    private $filters = array();
    private $binds = array();
    private $validFields = "";

    // <editor-fold defaultstate="collapsed" desc="operations">
    /**
     * $field = $value
     * @return string
     */

    const op_equals = "=";

    /**
     * $field >= $value
     * @return string
     */
    const op_majorequals = ">=";

    /**
     * $field <= $value
     * @return string
     */
    const op_minorquals = "<=";

    /**
     * $field >= $value
     * @return string
     */
    const op_majore = ">";

    /**
     * $field <= $value
     * @return string
     */
    const op_minor = "<";

    /**
     * $field != $value
     * @return string
     */
    const op_different = "!=";

    /**
     * $field like $value
     * @return string
     */
    const op_like = "LIKE";

    /**
      /**
     * $field like '%$value%'
     * @return string
     */
    const op_likeMatches = "LIKE %...%";

    /**
     * $field in (1,2,3)
     * @return string
     */
    const op_incommavalues = "incommavalues";

    /**
     * $field not in (1,2,3)
     * @return string
     */
    const op_notincommavalues = "notincommavalues";

    /**
     * $field in (1,2,3)
     * @return string
     */
    const op_inarrayvalues = "inarrayvalues";

    /**
     * $field not in (1,2,3)
     * @return string
     */
    const op_notinarrayvalues = "notinarrayvalues";

    /**
     * $value need separated by comma
     * eg 10,40
     * $field between field[0] and field[1]
     * $field between 10 and 40
     * @return string
     */
    const op_between = "between";

    /**
     * $value need separated by comma
     * eg 10,40
     * $field not between field[0] and field[1]
     * $field not between 10 and 40
     * @return string
     */
    const op_notbetween = "notbetween";

    /**
     * $field is null
     * @return string
     */
    const op_isnull = "isnull";

    /**
     * $field is not null
     * @return string
     */
    const op_notnull = "notnull";
    const op_injectsql = "injectsql";

// </editor-fold>

    function __construct($field, $value, $operator = null) {
        if (!isset($operator)) {
            $operator = dataFilter::op_equals;
        }

        $this->add($field, $value, $operator);
    }

    public function add($field, $value, $operator = null, $type = "AND") {
        if (!isset($operator)) {
            $operator = dataFilter::op_equals;
        }

        $this->filters[] = array($field, $value, $operator, $type);
        return $this;
    }

    private static function getCommaValues($string) {
        $arr = explode(",", $string);
        if (!issetArray($arr)) {
            return $string;
        }

        return self::getArrayValues($arr);
    }

    private static function getArrayValues($arr) {
        if (!issetArray($arr)) {
            return $arr;
        }

        $return = array();
        foreach ($arr as $str) {
            $return[] = "'" . trim($str) . "'";
        }

        return join(",", $return);
    }

    public function setValidFields($validFields) {
        $this->validFields = $validFields;
    }

    private function getArrKeys() {
        $validFields = $this->validFields;
        $arr = array();

        if (str_contains($validFields, ",")) {
            $str = explode(",", $validFields);
            foreach ($str as $key) {
                $arr[] = trim($key);
            }
        } elseif (is_string($validFields) && $validFields) {
            $arr[] = trim($validFields);
        } else {
            $arr = false;
        }

        return $arr;
    }

    public function validateValidFields() {
        $arrKeys = $this->getArrKeys();
        if (issetArray($arrKeys) && issetArray($this->filters)) {
            foreach ($this->filters as $filter) {
                $column = $filter[0];
                if (!in_array($column, $arrKeys)) {
                    if (___MyDebugger) {
                        $msg = join(", ", $arrKeys);
                        $msg = "Coluna '{$column}' desconhecida. Campos válidos: {$msg}";
                    } else {
                        $msg = "Coluna desconhecida na clausula where, para mais detalhes altere ___MyDebugger para true.";
                    }

                    throw new jquerycmsException($msg);
                }
            }
        }

        return true;
    }

    public function SqlSimple() {
        if (!issetArray($this->filters)) {
            return "";
        }

        $this->validateValidFields();

        $s = "";
        foreach ($this->filters as $filter) {
            $op = $filter[2];

            if ($s != "") {
                $s.= " {$filter[3]}";
            }

            if ($op == "LIKE %...%") {
                $s .= " {$filter[0]} LIKE '%{$filter[1]}%'";
            } elseif ($op == "incommavalues") {
                $s .= " {$filter[0]} IN (" . self::getCommaValues($filter[1]) . ")";
            } elseif ($op == "notincommavalues") {
                $s .= " {$filter[0]} NOT IN (" . self::getCommaValues($filter[1]) . ")";
            } elseif ($op == "inarrayvalues") {
                $s .= " {$filter[0]} IN (" . self::getArrayValues($filter[1]) . ")";
            } elseif ($op == "notinarrayvalues") {
                $s .= " {$filter[0]} NOT IN (" . self::getArrayValues($filter[1]) . ")";
            } elseif ($op == "between") {
                $vals = explode(",", $filter[1]);
                if (issetArray($vals) && isset($vals[0]) && isset($vals[1])) {
                    $s .= " {$filter[0]} BETWEEN {$vals[0]} AND {$vals[1]}";
                } else {
                    throw new jquerycmsException("\$value for op_between need separeted by comma. eg: 10,40");
                }
            } elseif ($op == "notbetween") {
                $vals = explode(",", $filter[1]);
                if (issetArray($vals) && isset($vals[0]) && isset($vals[1])) {
                    $s .= " {$filter[0]} NOT BETWEEN {$vals[0]} AND {$vals[1]}";
                } else {
                    throw new jquerycmsException("\$value for op_between need separeted by comma. eg: 10,40");
                }
            } elseif ($op == "isnull") {
                $s .= " {$filter[0]} IS NULL";
            } elseif ($op == "notnull") {
                $s .= " {$filter[0]} IS NOT NULL";
            } elseif ($op == "injectsql") {
                $s .= " {$filter[0]} {$filter[1]}";
            } else {
                $s .= " {$filter[0]} {$filter[2]} '{$filter[1]}'";
            }
        }

        return $s;
    }

    public function SqlParametrized() {
        if (!issetArray($this->filters)) {
            return "";
        }

        $this->validateValidFields();

        $s = "";
        $i = 0;
        foreach ($this->filters as $filter) {
            $op = $filter[2];
            $bindkey = preg_replace("/[^a-z0-9]/", "", $filter[0] . $i);

            if ($s != "") {
                $s.= " {$filter[3]}";
            }

            if ($op == "LIKE %...%") {
                $s .= " {$filter[0]} LIKE :$bindkey";
                $this->binds[$bindkey] = "%{$filter[1]}%";
            } elseif ($op == "incommavalues") {
                //$s .= " {$filter[0]} IN (:$bindkey)";
                //$this->binds[$bindkey] = self::getCommaValues($filter[1]);
				$s .= " {$filter[0]} IN (" . self::getCommaValues($filter[1]) . ")";
            } elseif ($op == "notincommavalues") {
                //$s .= " {$filter[0]} NOT IN (:$bindkey)";
                //$this->binds[$bindkey] = self::getCommaValues($filter[1]);
				$s .= " {$filter[0]} NOT IN (" . self::getCommaValues($filter[1]) . ")";
            } elseif ($op == "inarrayvalues") {
                $s .= " {$filter[0]} IN (:$bindkey)";
                $this->binds[$bindkey] = self::getArrayValues($filter[1]);
            } elseif ($op == "notinarrayvalues") {
                $s .= " {$filter[0]} NOT IN (:$bindkey)";
                $this->binds[$bindkey] = self::getArrayValues($filter[1]);
            } elseif ($op == "between") {
                $vals = explode(",", $filter[1]);
                if (issetArray($vals) && isset($vals[0]) && isset($vals[1])) {
                    $s .= " {$filter[0]} BETWEEN :{$bindkey}Zero AND :{$bindkey}One";
                    $this->binds[$bindkey . "Zero"] = $vals[0];
                    $this->binds[$bindkey . "One"] = $vals[1];
                } else {
                    throw new jquerycmsException("\$value for op_between need separeted by comma. eg: 10,40");
                }
            } elseif ($op == "notbetween") {
                $vals = explode(",", $filter[1]);
                if (issetArray($vals) && isset($vals[0]) && isset($vals[1])) {
                    $s .= " {$filter[0]} NOT BETWEEN :{$bindkey}Zero AND :{$bindkey}One";
                    $this->binds[$bindkey . "Zero"] = $vals[0];
                    $this->binds[$bindkey . "One"] = $vals[1];
                } else {
                    throw new jquerycmsException("\$value for op_between need separeted by comma. eg: 10,40");
                }
            } elseif ($op == "isnull") {
                $s .= " {$filter[0]} IS NULL";
            } elseif ($op == "notnull") {
                $s .= " {$filter[0]} IS NOT NULL";
            } elseif ($op == "injectsql") {
                $s .= " {$filter[0]} {$filter[1]}";
            } else {
                $s .= " {$filter[0]} {$filter[2]} :$bindkey";
                $this->binds[$bindkey] = $filter[1];
            }

            $i++;
        }

        return $s;
    }

    public function SqlBindOnStatement($statement) {
        if (!$statement instanceof PDOStatement) {
            throw new jquerycmsException("\$statement não é um PDOStatement válido!");
        }

        if (!issetArray($this->binds)) {
            $this->SqlParametrized();
        }

        foreach ($this->binds as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }

        return $statement;
    }

}

class dataOrder {
    /* http://stackoverflow.com/questions/5738141/pdo-parameters-not-working-at-all */

    private $validFields = "";
    private $order = array();

    /**
     * Inicia o obj dataOrder <br />
     * $validFields: sao os campos validos separados por virgula. ex 'cod, titulo, categoria, valor' <br />
     * $field: o campo para ordem. ex titulo
     * $orientation: Crescente ou decrescente: ASC|DESC
     * @param string $validFields
     * @param string $field
     * @param string $orientation
     */
    function __construct($field, $orientation = "ASC") {
        $this->add($field, $orientation);
    }

    public function setValidFields($validFields) {
        $this->validFields = $validFields;
    }

    private function getArrKeys() {
        $validFields = $this->validFields;
        $arr = array();

        if (str_contains($validFields, ",")) {
            $str = explode(",", $validFields);
            foreach ($str as $key) {
                $arr[] = trim($key);
            }
        } elseif (is_string($validFields) && $validFields) {
            $arr[] = trim($validFields);
        } else {
            $arr = false;
        }

        return $arr;
    }

    public function add($field, $orientation = "ASC") {
        $orientation = strtoupper($orientation);
        if ($orientation == "ASC" || $orientation == "DESC") {
            $orientation = $orientation;
        } else {
            $orientation = "";
        }

        $this->order[] = array($field, $orientation);
    }

    public function SqlOrder() {
        //validate
        $arrKeys = $this->getArrKeys();
        $arrOrder = $this->order;

        if (issetArray($arrKeys)) {
            $arrKeys[] = "RAND ()";
            $arrKeys[] = "";
        }

        $s = array();
        if (issetArray($arrOrder)) {
            foreach ($arrOrder as $ordem) {
                if (issetArray($arrKeys) && !in_array($ordem[0], $arrKeys)) {
                    if (___MyDebugger) {
                        $msg = join(", ", $arrKeys);
                        $msg = "Coluna '{$ordem[0]}' desconhecida. Campos válidos: {$msg}";
                    } else {
                        $msg = "Coluna desconhecida na clausula order by, para mais detalhes altere ___MyDebugger para true.";
                    }

                    throw new jquerycmsException($msg);
                }

                if ($ordem[0]) {
                    $s[] = $ordem[0] . " " . $ordem[1];
                }
            }

            return join(", ", $s);
        } else {
            return "";
        }
    }

}

class dataPager {

    private $Conexao;
    private $tabela;
    private $where = "";
    private $leftjoin = "";
    public $cssClass = "pagination";
    public $paginaAtual;
    public $recordsTotal;
    public $recordsPorPagina = 30;
    public $linkTemplate = "[url]?[query]";

    function __construct($paginaAtual, $recordsPorPagina, $Conexao, $tabela, $where, $leftjoin = "") {
        $this->recordsPorPagina = $recordsPorPagina;

        if ($paginaAtual > 0) {
            $this->paginaAtual = $paginaAtual;
        } else {
            $this->paginaAtual = 0;
        }

        $this->Conexao = $Conexao;
        $this->tabela = $tabela;
        $this->where = $where;
        $this->leftjoin = $leftjoin;

        $dados = dataListar($this->Conexao, $this->tabela, $this->where, "", "", "count(*) as contar", $this->leftjoin);
        if (isset($dados[0]["contar"])) {
            $this->recordsTotal = $dados[0]["contar"];
        }
    }

    public function getLimit() {
        $paginaAtual = $this->paginaAtual;
        $recordsPorPagina = $this->recordsPorPagina;

        $recordsAtual = (int) $paginaAtual * (int) $recordsPorPagina;

        return "$recordsAtual, $recordsPorPagina";
    }

    public function getLinkPage($page) {
        $linkTemplate = $this->linkTemplate;
        if ($page < 0) {
            $page = 0;
        }

        if ($page > $this->getTotalPages() - 1) {
            $page = $this->getTotalPages() - 1;
        }

        $url = Fncs_GetCurrentURL(true);
        $query = $_GET;
        $query["page"] = $page;

        $querystring = "";
        if (issetArray($query)) {
            foreach ($query as $key => $value) {
                if ($querystring) {
                    $querystring .= "&";
                }

                $querystring .= "$key=$value";
            }
        }

        $linkTemplate = str_replace('[url]', $url, $linkTemplate);
        $linkTemplate = str_replace('[query]', $querystring, $linkTemplate);
        $linkTemplate = str_replace('[page]', $page, $linkTemplate);

        return $linkTemplate;
    }

    public function getTotalPages() {
        return ceil($this->recordsTotal / $this->recordsPorPagina);
    }

    public function getPager() {
        $paginaAtual = $this->paginaAtual;
        $recordsPorPagina = $this->recordsPorPagina;
        $recordsTotal = $this->recordsTotal;
        $paginas = $this->getTotalPages();

        $s = "<div class='{$this->cssClass}'><ul>";
        for ($index = 0; $index < $paginas; $index++) {
            if ($index == 0) {
                //Prev
                if ($paginaAtual == 0) {
                    $s .= "<li class='disabled'><a class='disabled' href='{$this->getLinkPage($this->paginaAtual - 1)}'>«</a></li>";
                } else {
                    $s .= "<li><a href='{$this->getLinkPage($this->paginaAtual - 1)}'>«</a></li>";
                }
            }

            if ($paginaAtual == $index) {
                $s.= "<li class='disabled'><a class='active' href='{$this->getLinkPage($index)}'>" . intval($index + 1) . "</a></li>";
            } else {
                $s.= "<li><a href='{$this->getLinkPage($index)}'>" . intval($index + 1) . "</a></li>";
            }

            if ($index == $paginas - 1) {
                //Next
                if ($paginaAtual == $paginas - 1) {
                    $s .= "<li class='disabled'><a class='disabled' href='{$this->getLinkPage($this->paginaAtual + 1)}'>»</a></li>";
                } else {
                    $s .= "<li><a href='{$this->getLinkPage($this->paginaAtual + 1)}'>»</a></li>";
                }
            }
        }

        $s.= "</ul></div>";
        return $s;
    }

}