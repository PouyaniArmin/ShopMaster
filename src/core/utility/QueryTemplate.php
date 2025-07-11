<?php 



namespace App\Core\Utility;
/**
 * SQL query templates used by QueryBuilder.
 */
enum QueryTemplate{
    public const SELECT="SELECT %s ";
    public const WHERE=" WHERE %s %s %s ";
    public const AND=" AND %s %s %s ";
    public const OR=" OR %s %s %s ";
    public const INSERT="INSERT INTO %s (%s) VALUES (%s);";
    public const UPDATE="UPDATE %s SET %s ";
    public const DELETE="DELETE ";
    public const COUNT="SELECT COUNT(*) ";
}   