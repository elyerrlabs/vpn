<?php
// GENERATED CODE -- DO NOT EDIT!

namespace VPN\Proto\Wireguard;

/**
 */
class WireguardServiceClient extends \Vpn\Vendor\Grpc\BaseStub
{

  /**
   * @param string $hostname hostname
   * @param array $opts channel options
   * @param \Vpn\Vendor\Grpc\Channel $channel (optional) re-use channel object
   */
  public function __construct($hostname, $opts, $channel = null)
  {
    parent::__construct($hostname, $opts, $channel);
  }

  /**
   * @param \Vpn\Proto\Wireguard\MountRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function mount(
    \Vpn\Proto\Wireguard\MountRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/mount',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\InterfaceRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function umount(
    \Vpn\Proto\Wireguard\InterfaceRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/umount',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\InterfaceRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function down(
    \Vpn\Proto\Wireguard\InterfaceRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/down',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\InterfaceRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function up(
    \Vpn\Proto\Wireguard\InterfaceRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/up',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\InterfaceRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function restart(
    \Vpn\Proto\Wireguard\InterfaceRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/restart',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\AddPeerRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function addPeer(
    \Vpn\Proto\Wireguard\AddPeerRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/addPeer',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\DeletePeerRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function deletePeer(
    \Vpn\Proto\Wireguard\DeletePeerRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/deletePeer',
      $argument,
      ['\Vpn\Proto\Wireguard\MessageResponse', 'decode'],
      $metadata,
      $options
    );
  }

  /**
   * @param \Vpn\Proto\Wireguard\EmptyRequest $argument input argument
   * @param array $metadata metadata
   * @param array $options call options
   * @return \Vpn\Vendor\Grpc\UnaryCall
   */
  public function interfaces(
    \Vpn\Proto\Wireguard\EmptyRequest $argument,
    $metadata = [],
    $options = []
  ) {
    return $this->_simpleRequest(
      '/proto.wireguard.WireguardService/interfaces',
      $argument,
      ['\Vpn\Proto\Wireguard\ListInterfacesResponse', 'decode'],
      $metadata,
      $options
    );
  }

}
