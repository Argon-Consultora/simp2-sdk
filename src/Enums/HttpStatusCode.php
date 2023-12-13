<?php

namespace SIMP2\SDK\Enums;

enum HttpStatusCode: int
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
