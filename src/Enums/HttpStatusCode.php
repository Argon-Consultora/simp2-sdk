<?php

namespace SIMP2\SDK\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Continue()
 * @method static static SwitchingProtocols()
 * @method static static Processing()
 * @method static static EarlyHints()
 * @method static static OK()
 * @method static static Created()
 * @method static static Accepted()
 * @method static static NonAuthoritativeInformation()
 * @method static static NoContent()
 * @method static static ResetContent()
 * @method static static PartialContent()
 * @method static static MultipleChoices()
 * @method static static MovedPermanently()
 * @method static static Found()
 * @method static static SeeOther()
 * @method static static NotModified()
 * @method static static UseProxy()
 * @method static static Unused()
 * @method static static TemporaryRedirect()
 * @method static static PermanentRedirect()
 * @method static static BadRequest()
 * @method static static Unauthorized()
 * @method static static PaymentRequired()
 * @method static static Forbidden()
 * @method static static NotFound()
 * @method static static MethodNotAllowed()
 * @method static static NotAcceptable()
 * @method static static ProxyAuthenticationRequired()
 * @method static static RequestTimeout()
 * @method static static Conflict()
 * @method static static Gone()
 * @method static static LengthRequired()
 * @method static static PreconditionFailed()
 * @method static static RequestEntityTooLarge()
 * @method static static RequestURITooLong()
 * @method static static UnsupportedMediaType()
 * @method static static RequestedRangeNotSatisfiable()
 * @method static static ExpectationFailed()
 * @method static static ImATeapot()
 * @method static static MisdirectedRequest()
 * @method static static UnprocessableEntity()
 * @method static static Locked()
 * @method static static TooEarly()
 * @method static static UpgradeRequired()
 * @method static static PreconditionRequired()
 * @method static static TooManyRequests()
 * @method static static RequestHeaderFieldsTooLarge()
 * @method static static UnavailableForLegalReasons()
 * @method static static InternalServerError()
 * @method static static NotImplemented()
 * @method static static BadGateway()
 * @method static static ServiceUnavailable()
 * @method static static GatewayTimeout()
 * @method static static HTTPVersionNotSupported()
 * @method static static VariantAlsoNegotiates()
 * @method static static InsufficientStorage()
 * @method static static NetworkAuthenticationRequired()
 * @method static static WebserverIsReturningAnUnknownError()
 * @method static static ConnectionTimedOut()
 * @method static static ATimeoutOccurred()
 */
final class HttpStatusCode extends Enum
{
    const Continue = 100;
    const SwitchingProtocols = 101;
    const Processing = 102;
    const EarlyHints = 103;
    const OK = 200;
    const Created = 201;
    const Accepted = 202;
    const NonAuthoritativeInformation = 203;
    const NoContent = 204;
    const ResetContent = 205;
    const PartialContent = 206;
    const MultipleChoices = 300;
    const MovedPermanently = 301;
    const Found = 302;
    const SeeOther = 303;
    const NotModified = 304;
    const UseProxy = 305;
    const Unused = 306;
    const TemporaryRedirect = 307;
    const PermanentRedirect = 308;
    const BadRequest = 400;
    const Unauthorized = 401;
    const PaymentRequired = 402;
    const Forbidden = 403;
    const NotFound = 404;
    const MethodNotAllowed = 405;
    const NotAcceptable = 406;
    const ProxyAuthenticationRequired = 407;
    const RequestTimeout = 408;
    const Conflict = 409;
    const Gone = 410;
    const LengthRequired = 411;
    const PreconditionFailed = 412;
    const RequestEntityTooLarge = 413;
    const RequestURITooLong = 414;
    const UnsupportedMediaType = 415;
    const RequestedRangeNotSatisfiable = 416;
    const ExpectationFailed = 417;
    const ImATeapot = 418;
    const MisdirectedRequest = 421;
    const UnprocessableEntity = 422;
    const Locked = 423;
    const TooEarly = 425;
    const UpgradeRequired = 426;
    const PreconditionRequired = 428;
    const TooManyRequests = 429;
    const RequestHeaderFieldsTooLarge = 431;
    const UnavailableForLegalReasons = 451;
    const InternalServerError = 500;
    const NotImplemented = 501;
    const BadGateway = 502;
    const ServiceUnavailable = 503;
    const GatewayTimeout = 504;
    const HTTPVersionNotSupported = 505;
    const VariantAlsoNegotiates = 506;
    const InsufficientStorage = 507;
    const NetworkAuthenticationRequired = 511;
    const WebserverIsReturningAnUnknownError = 520;
    const ConnectionTimedOut = 522;
    const ATimeoutOccurred = 524;
}
