<?php


/**
 * Base class that represents a row from the 'j_notifications_resp_pers' table.
 *
 * Table de jointure entre la notification et les personnes dont on va mettre le nom dans le message.
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseJNotificationResponsableEleve extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'JNotificationResponsableElevePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        JNotificationResponsableElevePeer
	 */
	protected static $peer;

	/**
	 * The flag var to prevent infinit loop in deep copy
	 * @var       boolean
	 */
	protected $startCopy = false;

	/**
	 * The value for the a_notification_id field.
	 * @var        int
	 */
	protected $a_notification_id;

	/**
	 * The value for the pers_id field.
	 * @var        string
	 */
	protected $pers_id;

	/**
	 * @var        AbsenceEleveNotification
	 */
	protected $aAbsenceEleveNotification;

	/**
	 * @var        ResponsableEleve
	 */
	protected $aResponsableEleve;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [a_notification_id] column value.
	 * cle etrangere de la notification
	 * @return     int
	 */
	public function getANotificationId()
	{
		return $this->a_notification_id;
	}

	/**
	 * Get the [pers_id] column value.
	 * cle etrangere des personnes
	 * @return     string
	 */
	public function getResponsableEleveId()
	{
		return $this->pers_id;
	}

	/**
	 * Set the value of [a_notification_id] column.
	 * cle etrangere de la notification
	 * @param      int $v new value
	 * @return     JNotificationResponsableEleve The current object (for fluent API support)
	 */
	public function setANotificationId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->a_notification_id !== $v) {
			$this->a_notification_id = $v;
			$this->modifiedColumns[] = JNotificationResponsableElevePeer::A_NOTIFICATION_ID;
		}

		if ($this->aAbsenceEleveNotification !== null && $this->aAbsenceEleveNotification->getId() !== $v) {
			$this->aAbsenceEleveNotification = null;
		}

		return $this;
	} // setANotificationId()

	/**
	 * Set the value of [pers_id] column.
	 * cle etrangere des personnes
	 * @param      string $v new value
	 * @return     JNotificationResponsableEleve The current object (for fluent API support)
	 */
	public function setResponsableEleveId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->pers_id !== $v) {
			$this->pers_id = $v;
			$this->modifiedColumns[] = JNotificationResponsableElevePeer::PERS_ID;
		}

		if ($this->aResponsableEleve !== null && $this->aResponsableEleve->getResponsableEleveId() !== $v) {
			$this->aResponsableEleve = null;
		}

		return $this;
	} // setResponsableEleveId()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->a_notification_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->pers_id = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 2; // 2 = JNotificationResponsableElevePeer::NUM_HYDRATE_COLUMNS.

		} catch (Exception $e) {
			throw new PropelException("Error populating JNotificationResponsableEleve object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->aAbsenceEleveNotification !== null && $this->a_notification_id !== $this->aAbsenceEleveNotification->getId()) {
			$this->aAbsenceEleveNotification = null;
		}
		if ($this->aResponsableEleve !== null && $this->pers_id !== $this->aResponsableEleve->getResponsableEleveId()) {
			$this->aResponsableEleve = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(JNotificationResponsableElevePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = JNotificationResponsableElevePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aAbsenceEleveNotification = null;
			$this->aResponsableEleve = null;
		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(JNotificationResponsableElevePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$deleteQuery = JNotificationResponsableEleveQuery::create()
				->filterByPrimaryKey($this->getPrimaryKey());
			$ret = $this->preDelete($con);
			if ($ret) {
				$deleteQuery->delete($con);
				$this->postDelete($con);
				$con->commit();
				$this->setDeleted(true);
			} else {
				$con->commit();
			}
		} catch (Exception $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(JNotificationResponsableElevePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				JNotificationResponsableElevePeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (Exception $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aAbsenceEleveNotification !== null) {
				if ($this->aAbsenceEleveNotification->isModified() || $this->aAbsenceEleveNotification->isNew()) {
					$affectedRows += $this->aAbsenceEleveNotification->save($con);
				}
				$this->setAbsenceEleveNotification($this->aAbsenceEleveNotification);
			}

			if ($this->aResponsableEleve !== null) {
				if ($this->aResponsableEleve->isModified() || $this->aResponsableEleve->isNew()) {
					$affectedRows += $this->aResponsableEleve->save($con);
				}
				$this->setResponsableEleve($this->aResponsableEleve);
			}

			if ($this->isNew() || $this->isModified()) {
				// persist changes
				if ($this->isNew()) {
					$this->doInsert($con);
				} else {
					$this->doUpdate($con);
				}
				$affectedRows += 1;
				$this->resetModified();
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Insert the row in the database.
	 *
	 * @param      PropelPDO $con
	 *
	 * @throws     PropelException
	 * @see        doSave()
	 */
	protected function doInsert(PropelPDO $con)
	{
		$modifiedColumns = array();
		$index = 0;


		 // check the columns in natural order for more readable SQL queries
		if ($this->isColumnModified(JNotificationResponsableElevePeer::A_NOTIFICATION_ID)) {
			$modifiedColumns[':p' . $index++]  = 'A_NOTIFICATION_ID';
		}
		if ($this->isColumnModified(JNotificationResponsableElevePeer::PERS_ID)) {
			$modifiedColumns[':p' . $index++]  = 'PERS_ID';
		}

		$sql = sprintf(
			'INSERT INTO j_notifications_resp_pers (%s) VALUES (%s)',
			implode(', ', $modifiedColumns),
			implode(', ', array_keys($modifiedColumns))
		);

		try {
			$stmt = $con->prepare($sql);
			foreach ($modifiedColumns as $identifier => $columnName) {
				switch ($columnName) {
					case 'A_NOTIFICATION_ID':
						$stmt->bindValue($identifier, $this->a_notification_id, PDO::PARAM_INT);
						break;
					case 'PERS_ID':
						$stmt->bindValue($identifier, $this->pers_id, PDO::PARAM_STR);
						break;
				}
			}
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
		}

		$this->setNew(false);
	}

	/**
	 * Update the row in the database.
	 *
	 * @param      PropelPDO $con
	 *
	 * @see        doSave()
	 */
	protected function doUpdate(PropelPDO $con)
	{
		$selectCriteria = $this->buildPkeyCriteria();
		$valuesCriteria = $this->buildCriteria();
		BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
	}

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aAbsenceEleveNotification !== null) {
				if (!$this->aAbsenceEleveNotification->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aAbsenceEleveNotification->getValidationFailures());
				}
			}

			if ($this->aResponsableEleve !== null) {
				if (!$this->aResponsableEleve->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aResponsableEleve->getValidationFailures());
				}
			}


			if (($retval = JNotificationResponsableElevePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = JNotificationResponsableElevePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getANotificationId();
				break;
			case 1:
				return $this->getResponsableEleveId();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 *                    Defaults to BasePeer::TYPE_PHPNAME.
	 * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
	 * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
	 * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
	{
		if (isset($alreadyDumpedObjects['JNotificationResponsableEleve'][serialize($this->getPrimaryKey())])) {
			return '*RECURSION*';
		}
		$alreadyDumpedObjects['JNotificationResponsableEleve'][serialize($this->getPrimaryKey())] = true;
		$keys = JNotificationResponsableElevePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getANotificationId(),
			$keys[1] => $this->getResponsableEleveId(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aAbsenceEleveNotification) {
				$result['AbsenceEleveNotification'] = $this->aAbsenceEleveNotification->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
			}
			if (null !== $this->aResponsableEleve) {
				$result['ResponsableEleve'] = $this->aResponsableEleve->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
			}
		}
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = JNotificationResponsableElevePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setANotificationId($value);
				break;
			case 1:
				$this->setResponsableEleveId($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = JNotificationResponsableElevePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setANotificationId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setResponsableEleveId($arr[$keys[1]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(JNotificationResponsableElevePeer::DATABASE_NAME);

		if ($this->isColumnModified(JNotificationResponsableElevePeer::A_NOTIFICATION_ID)) $criteria->add(JNotificationResponsableElevePeer::A_NOTIFICATION_ID, $this->a_notification_id);
		if ($this->isColumnModified(JNotificationResponsableElevePeer::PERS_ID)) $criteria->add(JNotificationResponsableElevePeer::PERS_ID, $this->pers_id);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(JNotificationResponsableElevePeer::DATABASE_NAME);
		$criteria->add(JNotificationResponsableElevePeer::A_NOTIFICATION_ID, $this->a_notification_id);
		$criteria->add(JNotificationResponsableElevePeer::PERS_ID, $this->pers_id);

		return $criteria;
	}

	/**
	 * Returns the composite primary key for this object.
	 * The array elements will be in same order as specified in XML.
	 * @return     array
	 */
	public function getPrimaryKey()
	{
		$pks = array();
		$pks[0] = $this->getANotificationId();
		$pks[1] = $this->getResponsableEleveId();

		return $pks;
	}

	/**
	 * Set the [composite] primary key.
	 *
	 * @param      array $keys The elements of the composite key (order must match the order in XML file).
	 * @return     void
	 */
	public function setPrimaryKey($keys)
	{
		$this->setANotificationId($keys[0]);
		$this->setResponsableEleveId($keys[1]);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return (null === $this->getANotificationId()) && (null === $this->getResponsableEleveId());
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of JNotificationResponsableEleve (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
	{
		$copyObj->setANotificationId($this->getANotificationId());
		$copyObj->setResponsableEleveId($this->getResponsableEleveId());

		if ($deepCopy && !$this->startCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);
			// store object hash to prevent cycle
			$this->startCopy = true;

			//unflag object copy
			$this->startCopy = false;
		} // if ($deepCopy)

		if ($makeNew) {
			$copyObj->setNew(true);
		}
	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     JNotificationResponsableEleve Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     JNotificationResponsableElevePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new JNotificationResponsableElevePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a AbsenceEleveNotification object.
	 *
	 * @param      AbsenceEleveNotification $v
	 * @return     JNotificationResponsableEleve The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setAbsenceEleveNotification(AbsenceEleveNotification $v = null)
	{
		if ($v === null) {
			$this->setANotificationId(NULL);
		} else {
			$this->setANotificationId($v->getId());
		}

		$this->aAbsenceEleveNotification = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the AbsenceEleveNotification object, it will not be re-added.
		if ($v !== null) {
			$v->addJNotificationResponsableEleve($this);
		}

		return $this;
	}


	/**
	 * Get the associated AbsenceEleveNotification object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     AbsenceEleveNotification The associated AbsenceEleveNotification object.
	 * @throws     PropelException
	 */
	public function getAbsenceEleveNotification(PropelPDO $con = null)
	{
		if ($this->aAbsenceEleveNotification === null && ($this->a_notification_id !== null)) {
			$this->aAbsenceEleveNotification = AbsenceEleveNotificationQuery::create()->findPk($this->a_notification_id, $con);
			/* The following can be used additionally to
				guarantee the related object contains a reference
				to this object.  This level of coupling may, however, be
				undesirable since it could result in an only partially populated collection
				in the referenced object.
				$this->aAbsenceEleveNotification->addJNotificationResponsableEleves($this);
			 */
		}
		return $this->aAbsenceEleveNotification;
	}

	/**
	 * Declares an association between this object and a ResponsableEleve object.
	 *
	 * @param      ResponsableEleve $v
	 * @return     JNotificationResponsableEleve The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setResponsableEleve(ResponsableEleve $v = null)
	{
		if ($v === null) {
			$this->setResponsableEleveId(NULL);
		} else {
			$this->setResponsableEleveId($v->getResponsableEleveId());
		}

		$this->aResponsableEleve = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the ResponsableEleve object, it will not be re-added.
		if ($v !== null) {
			$v->addJNotificationResponsableEleve($this);
		}

		return $this;
	}


	/**
	 * Get the associated ResponsableEleve object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     ResponsableEleve The associated ResponsableEleve object.
	 * @throws     PropelException
	 */
	public function getResponsableEleve(PropelPDO $con = null)
	{
		if ($this->aResponsableEleve === null && (($this->pers_id !== "" && $this->pers_id !== null))) {
			$this->aResponsableEleve = ResponsableEleveQuery::create()->findPk($this->pers_id, $con);
			/* The following can be used additionally to
				guarantee the related object contains a reference
				to this object.  This level of coupling may, however, be
				undesirable since it could result in an only partially populated collection
				in the referenced object.
				$this->aResponsableEleve->addJNotificationResponsableEleves($this);
			 */
		}
		return $this->aResponsableEleve;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->a_notification_id = null;
		$this->pers_id = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
		$this->resetModified();
		$this->setNew(true);
		$this->setDeleted(false);
	}

	/**
	 * Resets all references to other model objects or collections of model objects.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect
	 * objects with circular references (even in PHP 5.3). This is currently necessary
	 * when using Propel in certain daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all referrer objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} // if ($deep)

		$this->aAbsenceEleveNotification = null;
		$this->aResponsableEleve = null;
	}

	/**
	 * Return the string representation of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->exportTo(JNotificationResponsableElevePeer::DEFAULT_STRING_FORMAT);
	}

} // BaseJNotificationResponsableEleve
