<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/iot/v1/resources.proto

namespace Google\Cloud\Iot\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A public key certificate format and data.
 *
 * Generated from protobuf message <code>google.cloud.iot.v1.PublicKeyCertificate</code>
 */
class PublicKeyCertificate extends \Google\Protobuf\Internal\Message
{
    /**
     * The certificate format.
     *
     * Generated from protobuf field <code>.google.cloud.iot.v1.PublicKeyCertificateFormat format = 1;</code>
     */
    private $format = 0;
    /**
     * The certificate data.
     *
     * Generated from protobuf field <code>string certificate = 2;</code>
     */
    private $certificate = '';
    /**
     * [Output only] The certificate details. Used only for X.509 certificates.
     *
     * Generated from protobuf field <code>.google.cloud.iot.v1.X509CertificateDetails x509_details = 3;</code>
     */
    private $x509_details = null;

    public function __construct() {
        \GPBMetadata\Google\Cloud\Iot\V1\Resources::initOnce();
        parent::__construct();
    }

    /**
     * The certificate format.
     *
     * Generated from protobuf field <code>.google.cloud.iot.v1.PublicKeyCertificateFormat format = 1;</code>
     * @return int
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * The certificate format.
     *
     * Generated from protobuf field <code>.google.cloud.iot.v1.PublicKeyCertificateFormat format = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setFormat($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Iot\V1\PublicKeyCertificateFormat::class);
        $this->format = $var;

        return $this;
    }

    /**
     * The certificate data.
     *
     * Generated from protobuf field <code>string certificate = 2;</code>
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * The certificate data.
     *
     * Generated from protobuf field <code>string certificate = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCertificate($var)
    {
        GPBUtil::checkString($var, True);
        $this->certificate = $var;

        return $this;
    }

    /**
     * [Output only] The certificate details. Used only for X.509 certificates.
     *
     * Generated from protobuf field <code>.google.cloud.iot.v1.X509CertificateDetails x509_details = 3;</code>
     * @return \Google\Cloud\Iot\V1\X509CertificateDetails
     */
    public function getX509Details()
    {
        return $this->x509_details;
    }

    /**
     * [Output only] The certificate details. Used only for X.509 certificates.
     *
     * Generated from protobuf field <code>.google.cloud.iot.v1.X509CertificateDetails x509_details = 3;</code>
     * @param \Google\Cloud\Iot\V1\X509CertificateDetails $var
     * @return $this
     */
    public function setX509Details($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Iot\V1\X509CertificateDetails::class);
        $this->x509_details = $var;

        return $this;
    }

}

